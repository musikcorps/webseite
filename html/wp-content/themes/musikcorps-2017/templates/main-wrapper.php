<?php

use Roots\Sage\Setup;
use Roots\Sage\Wrapper;

?>

<?php
do_action('get_header');
get_template_part(is_front_page() ? 'templates/header-front-page' : 'templates/header');
?>

    <div class="wrap container" role="document">
        <div class="content row">

            <main class="main columns small-12 <?= Setup\display_sidebar() ? "large-9" : "large-12" ?>">
                <?php include Wrapper\template_path(); ?>
            </main>

            <?php if (Setup\display_sidebar()) : ?>
                <aside class="sidebar columns small-12 large-3">
                    <?php include Wrapper\sidebar_path(); ?>
                </aside>
            <?php endif; ?>
        </div>
    </div>

<?php
do_action('get_footer');
get_template_part('templates/footer');
wp_footer();
?>