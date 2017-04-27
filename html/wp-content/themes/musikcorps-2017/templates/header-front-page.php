<header class="frontpage show-for-large-up desktop">

    <div class="header-vspacer row">
        <div class="header-content">

            <div class="link-homepage">
                <a href="<?= esc_url(home_url('/')); ?>">
                    <img src="<?= get_template_directory_uri(); ?>/dist/images/logo.png" />
                    <h1 class="brand"><?php bloginfo('name'); ?></h1>
                </a>
            </div>

            <nav class="primary-nav">
                <?php
                if (has_nav_menu('primary_navigation')) {
                    wp_nav_menu(['theme_location' => 'primary_navigation', 'menu_class' => 'left']);
                }
                ?>
            </nav>
            <div class="clearfix"></div>

        </div>
    </div>

</header>


<header class="frontpage hide-for-large-up mobile callout">
    <img src="<?= get_template_directory_uri(); ?>/dist/images/front-page-background.jpg" />
</header>
