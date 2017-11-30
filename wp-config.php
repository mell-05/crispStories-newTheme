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
define('DB_NAME', 'crispstories');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

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
define('AUTH_KEY',         'O$bTDOM)kgE#;me?tS%hT/Q<w&9KmA}~I`.HdhvXK0Yt:oqCW=}hL Kt.qXJ,Lf<');
define('SECURE_AUTH_KEY',  'I!NRSdx?XS<C*h](i8oXNjjLQ-U3_X8&+r)>tYymj~~-l!|*M/5^B9_|q4xs^NQU');
define('LOGGED_IN_KEY',    'n#aIL1Q&vo;{w]jib{4PbW2b]V6:HmZ=:dTNU} 3dJmFrrj2*G* A/L{<x:1>Q4b');
define('NONCE_KEY',        '6QXK)bBL}*D-yR|/A&<J~Pncxcl)g5TG{=Hlc-p}*R_e#NFImKf**gU)6fA#/YXQ');
define('AUTH_SALT',        '69;-b]4,5EdGCoxwU/pS6eA$/G~g[dE{e/Y?_j7p#D1$*&0J>=<LR{MSe+{M$lQ(');
define('SECURE_AUTH_SALT', 'dqizVO$1|[n:|=i>>WT2v$~92Byk(iN6^~qda5wpsEKd;IGK (xKXw:l*i=mX?&j');
define('LOGGED_IN_SALT',   '3q?TjwxLKRTUd!.@u vu))3(.i$!^g&YSy>DWy^L&oEp:HEz?]V;1&*!2f2lUoY}');
define('NONCE_SALT',       't^as=P7w:YuzHejl*~?5:)q5zyBk;_PR`U/%Ie(ur8n(LFn0!0f|`yfvcG#=6f~>');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
