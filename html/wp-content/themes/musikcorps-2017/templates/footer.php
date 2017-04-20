<hr />
<footer>
    <?php dynamic_sidebar('sidebar-footer'); ?>
    <div class="text-center">
        <div class="site-info">
            <a href="<?php echo esc_url( __( 'https://wordpress.org/', 'musikcorps-2017' ) ); ?>">
                <?php printf( esc_html__( 'Proudly powered by %s', 'musikcorps-2017' ), 'WordPress' ); ?>
            </a>
            <span class="sep"> | </span>
            <?php printf( esc_html__( 'Theme: %1$s by %2$s.', 'musikcorps-2017' ), 'Musikcorps 2017', '<a href="https://johannes-lauinger.de/" rel="designer">Johannes Lauinger</a>' ); ?>
        </div>
    </div>
</footer>
