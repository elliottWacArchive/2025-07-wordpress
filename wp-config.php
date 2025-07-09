<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpress' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

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
define( 'AUTH_KEY',         ')U3lWC7m=)IQeMX`U%kj[] w(qqx2ySv:&XYQ2Oqh` Ty}iG}X#@P$jD71P2a4u=' );
define( 'SECURE_AUTH_KEY',  ' n 5ko?Dv Pf4:Pegnw^70i ?P!g p<GP!nT3U5;@ww8~baqd0G-%J^!w~k+|JTj' );
define( 'LOGGED_IN_KEY',    '`dZGzWVvqi0IO0s^D|XQd*ZU#!a2yr]6E;Ag%[Ws&| M[aSlp,8+y1VGTD])Hmxh' );
define( 'NONCE_KEY',        'Ml &5T<FxLu50Y6J0?0mt>J|CS9`W_o#zANE$EWACv/,&|aQm8)1EyY<!2pnrj4P' );
define( 'AUTH_SALT',        '#Rs8i>K&u1)lY1Zo;Q>n4j02rIma`Aeg&iUyy+pW*^g4^OMX{V3zh& >hyM**}GU' );
define( 'SECURE_AUTH_SALT', '>OyS52_Q(cVtXz8HEG2oDaf/=y{JwX`Q|Gk.wl69P+drlpwHX%:j?%CRbgu2Fxy ' );
define( 'LOGGED_IN_SALT',   '5ivCM.~-WV`d6A,;Dq#CH35j~u%`(nwy]v@0Q]HlLXbvX;$6E6enyR/`;%l8m2^B' );
define( 'NONCE_SALT',       ';og%G-{v@24_Y<1eE%H=ZHDSLoT}k@C%{t:;^dG!3244:;)flS7HJBw~pPs%_6(=' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wp_';

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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
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
