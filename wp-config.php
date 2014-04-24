<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'depplus');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', '127.0.0.1');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         '*@*Fg57pefn{!+)g.uIF-R#jz)@54w8.aIH5Haz8MItP1L2$z%>.75mTz3$vg^)&');
define('SECURE_AUTH_KEY',  'IaTNq+/}-R`G$fd+e=NF~}.zCn{T1]G8u+[C6:I{TTBel%n=4L]=_f+$f/lvI[]7');
define('LOGGED_IN_KEY',    '3%UddelI8eCP[MyepPHU&}[s,h?uXII:}-+fdMfRk@+NU3]o8)cD2V!>Piii-?vU');
define('NONCE_KEY',        '?Gi+_awEf#VU|Q?r^q 0wh{v+|LF%[V/26SM^T-B+gYCM,nLDbb<mST@Ly%.{!h.');
define('AUTH_SALT',        '~p&A|dp$T.[+-s+=r6!:d.(LO-WqI;[!?xBS4Xbl6>Y$ 0uYjrJrmF%-]@o Z=R-');
define('SECURE_AUTH_SALT', '$M!FX1IxsnGLWd]r+,R>tk4|ohWW7PTGc<>_vwD#Z,{Z|)h,S;:-Fe1V.5,CL!6a');
define('LOGGED_IN_SALT',   ';tL_Hs}I7f{Q:l.4,$k#p[EDCuy?FWH}*{E%1k3Z[>WPeYt@qv&_TpZknP8 F/WA');
define('NONCE_SALT',       'SbU*,6KL=&/D|*k[b7Jt/.G9_0a{BD6=-Y7^p-UE_(LrM{a>6`:}O(f`awp.9;uF');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', 'vi_VN');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
