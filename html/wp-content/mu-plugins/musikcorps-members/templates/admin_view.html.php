<?php

$table = new Musikcorps\MembersListTable();
$table->prepare_items();

?>

<div class="wrap">
    <h1 class="wp-heading-inline"><?= __('Mitglieder', 'musikcorps') ?></h1>
    <a href="#" class="page-title-action">Erstellen</a>
    <?= $table->display() ?>
</div>

<style>
    #col_id { width: 50px; }
</style>
