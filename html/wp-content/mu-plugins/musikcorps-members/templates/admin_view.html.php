<?php

$table = new Musikcorps\MembersListTable();
$table->prepare_items();

?>

<div class="wrap">
    <h1 class="wp-heading-inline"><?= __('Mitglieder', 'musikcorps') ?></h1>
    <a href="#" class="page-title-action">Erstellen</a>

    <?php if (isset($this->message)): ?><div class="success"><p><?php echo $this->message; ?></p></div><?php endif; ?>

    <?= $table->display() ?>
</div>


<style>
    #id { width: 50px; }
    .success { margin: 15px 0 0; background: #fff; border-left: 4px solid #46b450; box-shadow: 0 1px 1px 0 rgba(0,0,0,.1); padding: 1px 12px; }
    .success p { padding: 2px; margin: .5em 0; }
</style>
