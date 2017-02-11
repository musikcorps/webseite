<?php

# Load composer libraries
require_once(dirname(__DIR__) . '/vendor/autoload.php');

$root_dir = dirname(__DIR__);
$webroot_dir = $root_dir . '/html';

/**
 * Use Dotenv to set required environment variables and load .env file in root
 * Seravo provides all needed envs for WordPress by default.
 * If you want to have more envs put them into .env file
 * .env file is also heavily used in development
 */
if (file_exists($root_dir . '/.env')) {
  Dotenv::makeMutable();
  Dotenv::load($root_dir);
}

/**
 * DB settings
 * You can find the credentials by running $ wp-list-env
 */
define('DB_NAME', getenv('DB_NAME'));
define('DB_USER', getenv('DB_USER'));
define('DB_PASSWORD', getenv('DB_PASSWORD'));
define('DB_PORT', getenv('DB_PORT') ? getenv('DB_PORT') : 3306 );
define('DB_HOST', getenv('DB_HOST') ? getenv('DB_HOST') . ':' . DB_PORT : '127.0.0.1:3306' );
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', '');
$table_prefix = getenv('DB_PREFIX') ? getenv('DB_PREFIX') : 'wp_';

/**
 * URLs
 */
define('WP_HOME', getenv('BLOG_URL'));
define('WP_SITEURL', WP_HOME . "/wp");

/**
 * Content Directory is moved out of the wp-core.
 */
define('CONTENT_DIR', '/wp-content');
define('WP_CONTENT_DIR', $webroot_dir . CONTENT_DIR);
define('WP_CONTENT_URL', WP_HOME . CONTENT_DIR);

/**
 * Don't allow any other write method than direct
 */
define( 'FS_METHOD', 'direct' );

/**
 * Authentication Unique Keys and Salts
 * You can find them by running $ wp-list-env
 */
define('AUTH_KEY',         getenv('AUTH_KEY'));
define('SECURE_AUTH_KEY',  getenv('SECURE_AUTH_KEY'));
define('LOGGED_IN_KEY',    getenv('LOGGED_IN_KEY'));
define('NONCE_KEY',        getenv('NONCE_KEY'));
define('AUTH_SALT',        getenv('AUTH_SALT'));
define('SECURE_AUTH_SALT', getenv('SECURE_AUTH_SALT'));
define('LOGGED_IN_SALT',   getenv('LOGGED_IN_SALT'));
define('NONCE_SALT',       getenv('NONCE_SALT'));

/**
 * SSL ADMIN
 * Allow overriding it in dev environment so we can use phantomjs to test logging in.
 */
define('FORCE_SSL_ADMIN', getenv('FORCE_SSL_ADMIN') === false ? true : getenv('FORCE_SSL_ADMIN') === "true");

/**
 * Secure file edits against malware
 */
define('DISALLOW_FILE_EDIT', true); /* this disables the theme/plugin file editor */

/*
 * Auto activated plugins
 * These plugins will be activated automatically when this is installed
 */
define('WP_AUTO_ACTIVATE_PLUGINS',"google-analytics-dashboard-for-wp");

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);
define('WP_DEBUG_DISPLAY', false);
define('SCRIPT_DEBUG', false);

/**
 * Log error data but don't show it in the frontend.
 */
ini_set('log_errors', 'On');

/** Absolute path to the WordPress directory. */
if (!defined('ABSPATH')) {
  define('ABSPATH', $webroot_dir . '/wp/');
}

require_once(ABSPATH . 'wp-settings.php');

