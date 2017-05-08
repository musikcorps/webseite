<?php
/*
Plugin Name:  Wordpress Updates despite VCS
Plugin URI:   https://github.com/musikcorps/webseite
Description:  This enables the automatic updating system, even though the deployment is from a VCS repo.
Version:      1.0.0
Author:       Johannes Lauinger
Author URI:   https://johannes-lauinger.de/
License:      MIT License
*/


namespace Musikcorps;


class EnableAutoUpdatesPlugin {

    public function __construct() {
        add_filter( 'automatic_updates_is_vcs_checkout', '__return_false', 1 );
    }
}


new EnableAutoUpdatesPlugin();
