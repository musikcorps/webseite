<header>
    <h1><a href="<?= esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a></h1>
    <hr />
    <?php
        if (has_nav_menu('primary_navigation')) {
            wp_nav_menu(['theme_location' => 'primary_navigation', 'menu_class' => 'left']);
        }
    ?>
    <hr />
    <div class="panel">
        <p>A simple panel to test Foundation 5.</p>
    </div>
    <hr />
</header>