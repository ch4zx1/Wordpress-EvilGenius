<?php

//Begin Really Simple SSL session cookie settings
@ini_set('session.cookie_httponly', true);
@ini_set('session.cookie_secure', true);
@ini_set('session.use_only_cookies', true);
//END Really Simple SSL
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'evilge23_wp687' );

/** Database username */
define( 'DB_USER', 'evilge23_wp687' );

/** Database password */
define( 'DB_PASSWORD', '3S[m2C)2op' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'dxtnpmori3cazmifqopqtnvobbum0mmlfioror8kk8li1f3mn2cxynvctqp2lrhn' );
define( 'SECURE_AUTH_KEY',  'efobhabydkcmynleoei8ucu1yiw2vluxpkb3iot7xsrqigjvh4uyut48pbgtpjnf' );
define( 'LOGGED_IN_KEY',    'cb7ixhuzijl9o2aefp08eatb1vyvm7pnaog0gkvlmgjvolbpot8ff1bpof9vnogt' );
define( 'NONCE_KEY',        'jxyxjyz22etzj4eg652jic49cmb1zswv504gja1c4avhngeb1dq0uexhyaupcjnq' );
define( 'AUTH_SALT',        '7dllpo9mhtzerhtqemjykqwxedwhv8mvyyylubzxsmtzwk6h88vt9p372sgus2ce' );
define( 'SECURE_AUTH_SALT', 't5ppvmx3ixkgrzrmmzvpowomjn3lh8am7eqiodvgtfcuytlcfbgzzxgytkm81v3d' );
define( 'LOGGED_IN_SALT',   'ebftbxu369bnbrt0ee9yvrozigggpjqifdzni11sliippthsiv79cqkkqmz9ps3v' );
define( 'NONCE_SALT',       'nvd7sbmva7bfopzyioaojhuxo4uckqcsjcqa3giajwfxqdbitovq65nebtcwkl56' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wpix_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
