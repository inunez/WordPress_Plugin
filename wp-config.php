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
define('DB_NAME', 'wordpress_db');

/** MySQL database username */
define('DB_USER', 'wordpress_user');

/** MySQL database password */
define('DB_PASSWORD', 'columbia');

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
define('AUTH_KEY',         'uh8w8Rb_47,-Myf+%PD1kT$b2$dIs)<0rJQLVE^J1$=bs(vtQ #5G<UwIa:!uh/t');
define('SECURE_AUTH_KEY',  'urhOQsd&>ogUZPniyUX?2_E.rTHU_:lls>DpabG9l6IXjOaT0?nzN-|3G;H ajMy');
define('LOGGED_IN_KEY',    'c0=L:-=-0F+G#Cx#$WCYO(Z?rD/^jvGI:6kFxJ+kA8GA3F|q,mOk=G6#6*?ny1xJ');
define('NONCE_KEY',        'oNl5=o,+wQTO%vfL2|u/d]s79:<gYc5,&,vD$4QD]+r|~;vvxax|UjT[B+rSW1yK');
define('AUTH_SALT',        '1j6Th-n,VykUvHh(B]+f7TTlXy_>0F~sf2)2Qi,&=!1ICFzy[4^s-v2Sn=#/jQt[');
define('SECURE_AUTH_SALT', 'Iss+%t|-vuc_-[yt%+&E-|eTj1vAk.<u{hcq0BsMJ#v]+y^MIwk,4ttUgD,!xG=D');
define('LOGGED_IN_SALT',   'I@=m8iHBEJ04ngy^VQ&xTmH?kW<nJ93{H,?w`3_:Rn?z?0Q1Xy~|tm%z:P}[09yh');
define('NONCE_SALT',       'vk94FOb04[&!SF|>=s;0sdvBT8Cf+cFi.e;9A@dkujjDV`_x|x._vs8iO>15K|?C');

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
