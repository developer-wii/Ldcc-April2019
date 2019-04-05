<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', "samedayd_wp819");

/** MySQL database username */
define('DB_USER', "samedayd_wp819");

/** MySQL database password */
define('DB_PASSWORD', "Sp9(.VP50a");

/** MySQL hostname */
define('DB_HOST', "localhost:3306");

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '3uJG8M%^fYO;AvCH(K{`>eh80p!C,zJZv@c`}=I8gautj5kh5q/:fu3mg6)#g6,g');
define('SECURE_AUTH_KEY',  ':*6oQq^}on`}mG;z|v@xVE-2t nV`Vy[Ono7wmJH/NecK9N?}xYO-<lhfgg2Gw3D');
define('LOGGED_IN_KEY',    'b,h%PZ5DL(GFu-Ej0(y6H!B9W)[j?@n5sEN:vA]pzYF#BTk<h#S1+/A(IzN=xwzM');
define('NONCE_KEY',        '/ r5j(GR:9%r9Q~>^|s%,?d2=q*,~jlkue`HjZl}i/@ v7H%6L&*umv0&T}YT$$E');
define('AUTH_SALT',        '[!Yu#rLJUgHZ7jg>6MLp([qjMG~VhchLaE:Ic$e6{Dn3;nK|U95IK7hO+S!aemR)');
define('SECURE_AUTH_SALT', 'K!g3N2S~0VJqyYhM-S~s_^CLowqmGp+p?qivd.3OiX49s0OJM0(GoU7q#@dY:q;4');
define('LOGGED_IN_SALT',   '8GoV#tV!,5kgAMTFo<xddt9]w#S-Y_9zfsgfjcm4&NK~~!:Sh<LCMm%l^PH?xVe/');
define('NONCE_SALT',       'chl1l0OujUVAV!}>oD?K^vE1??78h}zB+TV R C_W#)NNH!3N.ooLHtXl,-p]z($');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = "wpbd_";

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);
define( 'WP_MEMORY_LIMIT', '1024M' );
/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
