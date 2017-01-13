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
define('DB_NAME', 'whiteleafproduction');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

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
define('AUTH_KEY',         'N DnvHa87&,H:Kp>_wcDyJiOFHRR ndIFpq9H*#p2,_VzC[Y[h>!t@4bgFuxSS+H');
define('SECURE_AUTH_KEY',  'bql^?5u6:m=m/[4BHw6h.7`A+`m}G#mNEj]}Kw6`fdK@:8H?jG/n:Z7G=1]|%z,I');
define('LOGGED_IN_KEY',    'sQ~DP&?}1OVf~,6!?0n[!Hx@D`KLx`4< KlPHk_:y%VK.b_o/``VYQI/SE=t|8!T');
define('NONCE_KEY',        'kOK{R.mJy]CKQXKy9e)&>Do#n!NkC&NU@8Im+{roXS`A)N)G>@4/]*vD<}Lmf-Od');
define('AUTH_SALT',        ';-]rQ9OY]8tuN,hz4J-6JXL_C2B56GI` U~OfuRUBkU~]!!c^~}o2X)`m2mZs)k>');
define('SECURE_AUTH_SALT', 'u?sbcXwj?h vMn|H/{i3$eUWRsS(J?T_NNhRU)cTeH-3NGT]K,7ZSqPA(@s z)q/');
define('LOGGED_IN_SALT',   '.xPU!]W:30rp4q^h=$0=bNn+,dDH?t`z}c.Eh;d%dk`#%-2e &A]L+krw1O_meE;');
define('NONCE_SALT',       '5B$H`}wHf59DG~PLMm52.Cn]5l/u%0!3A6t3xgKprNh6-Ph1]]?EdPc396.?nP4d');

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
