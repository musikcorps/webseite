<?php

namespace WordPress;

use Composer\Script\Event;

class Installer {
  /**
   * Remove wp-content from wordpress and symlink it to correct location
   *
   * @param Composer\Script\Event $event - This is the way composer talks to it's plugins
   */
  public static function symlinkWPContent(Event $event) {
    $io = $event->getIO();
    $root = dirname(dirname(__DIR__));
    $wp_core_content_folder = "{$root}/html/wordpress/wp-content";
    $wp_content_folder = "{$root}/html/wp-content";

    if (!is_link($wp_core_content_folder)) {
      if(file_exists($wp_core_content_folder)) {
        self::rrmdir($wp_core_content_folder);
        // Symlink shouldn't be necessary
        // It just makes everything seem more normal and fixes some problems with bad plugins
        symlink("../wp-content", $wp_core_content_folder);
        $io->write("Removed wp-content from core and symlinked it to ../wp-content");
      }
    }
  }

  /**
   * Remove dir recursively
   *
   * @param String $dir - path to folder to be destroyed
   */
  public static function rrmdir($dir) {
    foreach(glob($dir . '/*') as $file) {
      if(is_dir($file)) Installer::rrmdir($file); else unlink($file);
    } rmdir($dir);
  }
}
