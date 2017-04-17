<?php

namespace Musikcorps;

if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}


class MembersListTable extends \WP_List_Table {

    private $table_name;

    public function __construct() {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'mc_members';
        parent::__construct( array(
          'singular' => 'wp_member',
          'plural' => 'wp_members',
          'ajax' => false
        ));
    }

    public function get_columns() {
        return $columns = array(
            'col_id' => __('ID'),
            'col_firstname' => __('Vorname'),
            'col_lastname' => __('Nachname'),
            'col_instrument' => __('Instrument'),
            'col_register' => __('Register'),
            'col_birthday' => __('Geburtstag'),
            'col_active_since' => __('Aktiv seit'),
            'col_abzeichen' => __('Abzeichen'),
        );
    }

    public function get_sortable_columns() {
        return $sortable = array(
            'col_id' => 'id',
            'col_firstname' => 'firstname',
            'col_lastname' => 'lastname',
            'col_instrument' => 'instrument',
            'col_register' => 'register',
            'col_birthday' => 'birthday',
            'col_active_since' => 'active_since',
            'col_abzeichen' => 'abzeichen',
        );
    }

    public function prepare_items() {
        global $wpdb;

        $per_page = 25;
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();

        $this->_column_headers = array($columns, $hidden, $sortable);

        $this->process_bulk_action();

        $total_items = $wpdb->get_var("SELECT COUNT(id) FROM $this->table_name");

        $paged = isset($_REQUEST['paged']) ? max(0, intval($_REQUEST['paged']) - 1) : 0;
        $orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array_keys($this->get_sortable_columns()))) ? $_REQUEST['orderby'] : 'firstname';
        $order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? $_REQUEST['order'] : 'asc';

        $this->items = $wpdb->get_results($wpdb->prepare("SELECT * FROM $this->table_name ORDER BY $orderby $order LIMIT %d OFFSET %d", $per_page, $paged), ARRAY_A);

        $this->set_pagination_args(array(
            'total_items'   => $total_items,
            'per_page'      => $per_page,
            'total_pages'   => ceil($total_items / $per_page),
        ));
    }

    public function display_rows() {
        $records = $this->items;
        list($columns, $hidden) = $this->get_column_info();
        if (!empty($records)) {
            foreach ($records as $rec) {
                echo '<tr id="record_'.$rec->id.'">';
                foreach ($columns as $column_name => $column_display_name) {
                    $class = "class='$column_name column-$column_name'";
                    $style = "";
                    if (in_array($column_name, $hidden)) $style = ' style="display:none;"';
                    $attributes = $class . $style;
                    //$editlink  = '/wp-admin/member.php?action=edit&id='.(int)$rec->id;
                    switch ($column_name) {
                        case "col_id":  echo '<td '.$attributes.'>'.stripslashes($rec["id"]).'</td>'; break;
                        case "col_firstname": echo '<td '.$attributes.'>'.stripslashes($rec["firstname"]).'</td>'; break;
                        case "col_lastname": echo '<td '.$attributes.'>'.stripslashes($rec["lastname"]).'</td>'; break;
                        case "col_instrument": echo '<td '.$attributes.'>'.stripslashes($rec["instrument"]).'</td>'; break;
                        case "col_register": echo '<td '.$attributes.'>'.stripslashes($rec["register"]).'</td>'; break;
                        case "col_birthday": echo '<td '.$attributes.'>'.stripslashes($rec["birthday"]).'</td>'; break;
                        case "col_active_since": echo '<td '.$attributes.'>'.stripslashes($rec["active_since"]).'</td>'; break;
                        case "col_abzeichen": echo '<td '.$attributes.'>'.stripslashes($rec["abzeichen"]).'</td>'; break;
                    }
                }
                echo'</tr>';
            }
        }
    }
}
