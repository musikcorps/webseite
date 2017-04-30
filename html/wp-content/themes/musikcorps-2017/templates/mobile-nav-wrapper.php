<?php

use Roots\Sage\Setup;
use Roots\Sage\Wrapper;

?>

<header class="frontpage mobile">
    <div class="off-canvas-wrap" data-offcanvas>
        <div class="inner-wrap">

            <nav class="tab-bar hide-for-large-up">
                <section class="left-small">
                    <a class="left-off-canvas-toggle menu-icon" href="#"><span></span></a>
                </section>
                <section class="middle tab-bar-section">
                    <a href="<?= esc_url(home_url('/')); ?>">
                        <h1 class="title brand"><?php bloginfo('name'); ?></h1>
                    </a>
                </section>
                <section class="right-small logo">
                    <?php if (!has_custom_logo()): ?>
                        <img src="<?= get_template_directory_uri(); ?>/dist/images/logo.png" />
                    <?php else: ?>
                        <?php the_custom_logo() ?>
                    <?php endif ?>
                </section>
            </nav>

            <aside class="left-off-canvas-menu">
                <div class="main-menu-label"><span>Hauptmenü</span></div>
                <ul class="off-canvas-list">
                    <li><a href="<?= esc_url(home_url('/')); ?>">Startseite</a></li>
                </ul>
                <?php
                if (has_nav_menu('primary_navigation')) {
                    wp_nav_menu(['theme_location' => 'primary_navigation', 'menu_class' => 'off-canvas-list']);
                }
                ?>
            </aside>


            <section class="main-section">
                <?php get_template_part('templates/main-wrapper'); ?>
            </section>


            <a class="exit-off-canvas"></a>
        </div>
    </div>
</header>