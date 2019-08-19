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
define( 'DB_NAME', 'wordpress' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '54321' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'SJk0&AUqLL1%C$8_zUc_f,]dIaX,CEW?d9?Sar7|=>PIa.a@iF7e Q.7W[SrKsG1' );
define( 'SECURE_AUTH_KEY',  'PXHaZesWZYe&d{5>8W6p2z#ki9T`$`X}=xw. I?-$,+A |cwQ+i3q1>fwcu>,.c4' );
define( 'LOGGED_IN_KEY',    '+u^SKBcWSwRhCc~6/}YaER@JPDkFRX7[(j96|wI`^LF7+$/;qGOq31X8{zf=lOH=' );
define( 'NONCE_KEY',        '7!R8r,9mpS)j}o(Use0,/kg,^_2BMBx9;MtSi7f#a5oQIKtNXQ5R+ve=x9 bM@(]' );
define( 'AUTH_SALT',        'k,,/?!E^1YQE](*J7S}.2&:1z/nPLz;^IM#>9&*CGEJLOw(D?:;vUx &qe*:7x8J' );
define( 'SECURE_AUTH_SALT', '*LIOg=P`{k|c]t-~oIqg*+E:Q_0S7+-$.?1wlp11|^l oWsu[`W9*gKJ3aFj2*a,' );
define( 'LOGGED_IN_SALT',   ')885t~I!q[Z6qwdyuL)E)td|H!oXeq3GbwxF.*>j ]SRNf$yZ^*2A#my*U.8pUGe' );
define( 'NONCE_SALT',       'b75BJ[6,[1k{1kEEN`PUc4ZpXUqQN ]bf:FBeClDpsb2EJzE?UJ)^pSF$kDcf2^b' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
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
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
