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
            'id' => __('ID'),
            'firstname' => __('Vorname'),
            'lastname' => __('Nachname'),
            'instrument' => __('Instrument'),
            'register' => __('Register', 'musikcorps'),
            'birthday' => __('Geburtstag'),
            'email' => __('E-Mail'),
        );
    }

    public function get_sortable_columns() {
        return $sortable = array(
            'id' => array('id', true),
            'firstname' => array('firstname', true),
            'lastname' => array('lastname', true),
            'instrument' => array('instrument', true),
            'register' => array('register', true),
            'birthday' => array('birthday', true),
            'email' => array('email', true),
        );
    }

    public function prepare_items() {
        global $wpdb;

        $per_page = 25;
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();

        $this->_column_headers = array($columns, $hidden, $sortable);

        $total_items = $wpdb->get_var("SELECT COUNT(id) FROM $this->table_name");

        $paged = isset($_REQUEST['paged']) ? max(0, intval($_REQUEST['paged']) - 1) : 0;
        $orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array_keys($this->get_sortable_columns()))) ? $_REQUEST['orderby'] : 'firstname';
        $order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? $_REQUEST['order'] : 'asc';
        $this->items = $wpdb->get_results($wpdb->prepare("SELECT *, DATE_FORMAT(birthday, '%%d.%%m.%%Y') AS birthday_f FROM $this->table_name ORDER BY $orderby $order LIMIT %d OFFSET %d", $per_page, $paged), ARRAY_A);

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
                    $editlink  = 'admin.php?page=musikcorps_members&id='.$rec["id"];
                    switch ($column_name) {
                        case "id":  echo '<td '.$attributes.'>'.stripslashes($rec["id"]).'</td>'; break;
                        case "firstname": echo '<td '.$attributes.'><a href="'.$editlink.'">'.stripslashes($rec["firstname"]).'</a></td>'; break;
                        case "lastname": echo '<td '.$attributes.'><a href="'.$editlink.'">'.stripslashes($rec["lastname"]).'</a></td>'; break;
                        case "instrument": echo '<td '.$attributes.'>'.stripslashes($rec["instrument"]).'</td>'; break;
                        case "register": echo '<td '.$attributes.'>'.stripslashes($rec["register"]).'</td>'; break;
                        case "birthday": echo '<td '.$attributes.'>'.stripslashes($rec["birthday_f"]).'</td>'; break;
                        case "email": echo '<td '.$attributes.'>'.stripslashes($rec["email"]).'</td>'; break;
                    }
                }
                echo'</tr>';
            }
        }
    }
}
