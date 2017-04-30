<?php

use Roots\Sage\Setup;
use Roots\Sage\Wrapper;

?>

<!doctype html>
<html <?php language_attributes(); ?>>

    <?php get_template_part('templates/head'); ?>

    <body <?php body_class(); ?>>

        <?php get_template_part('templates/mobile-nav-wrapper'); ?>

        <?php if (WP_DEBUG): ?>
            <!-- Browser-Sync -->
            <script id="__bs_script__">//<![CDATA[
            document.write("<script async src='http://HOST:3000/browser-sync/browser-sync-client.js?v=2.18.8'><\/script>".replace("HOST", location.hostname));
            //]]></script>
        <?php endif ?>

        <?php wp_footer(); ?>
    </body>
</html>
