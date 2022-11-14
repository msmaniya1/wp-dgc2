<?php
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
define( 'DB_NAME', 'wp-dgc2' );

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
define( 'AUTH_KEY',         'trq[: `AJX}wyA9^f0xeheIm:<tpm)@AyP]FAtUdQZ%=[Zo ,QF!@5(Ox%pUFyK?' );
define( 'SECURE_AUTH_KEY',  'N%<B@uG<kQM5qbB8j`yHTRTZW[+S5GcUn:Mz2=PCk#.*ssH-Iz|OX/X])G<dYYne' );
define( 'LOGGED_IN_KEY',    'sDZGs*IU5d2;c):yQ[fF.RQt0{;S x 0CID4#X*mij>q,Qia|WmZ4oGF`U>nl#7@' );
define( 'NONCE_KEY',        ']HuE3zPuEz5$P^#,@][R;Sc9FE>>=tM(sWMRd~j{( Wr{a6P{|>eLhW~ns~untpe' );
define( 'AUTH_SALT',        '<|{gUp-ao7MW>WJFu]$q$;]#77U+l`lp[WIRv]6+-v7(O5Jmo~&?$J80iOio41hx' );
define( 'SECURE_AUTH_SALT', 'h VH9=$v%NP)8rZcTUyC-d4xI7!$V.`Y{qwe`4j5dgkD)L>OyX6IfkQ0$+~H2zjM' );
define( 'LOGGED_IN_SALT',   '51Uf0#g4{(wX6]A!Bn&+f(/&tdh9)rFq/t.htfpXDy FA8w=^E><cScby 9Jh=fF' );
define( 'NONCE_SALT',       '/i<@;)E1DbRif#1 =fU^,2w!,D/+VT`R9h=67J#B ^<3hbP:j@|h<9PX=|wf<#R;' );

/**#@-*/

/**
 * WordPress database table prefix.
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
