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

 * @link https://wordpress.org/support/article/editing-wp-config-php/

 *

 * @package WordPress

 */



// ** MySQL settings - You can get this info from your web host ** //

/** The name of the database for WordPress */

define( 'DB_NAME', "sjswqqex_nat_wp_acesco" );



/** MySQL database username */

define( 'DB_USER', "sjswqqex_nat_wp_acesco" );



/** MySQL database password */

define( 'DB_PASSWORD', "r\$Z6)bMcIVMq" );



/** MySQL hostname */

define( 'DB_HOST', "localhost" );



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

define( 'AUTH_KEY',         ']P7NA51$jg2We8(`$,~(*#ZxyU2T!0LS rYzB]A@4aCB#Wp2TFWlvX5^UE]Z9U{r' );

define( 'SECURE_AUTH_KEY',  '+,HKM@|=N:^b+et}^bB)$g`9@xeM`zT0PN!0dI{VKi}wv_0LqY`OUj,j?P$I)V }' );

define( 'LOGGED_IN_KEY',    'g9u~020id$vfWelDZV `.g%b*0.njV7{mz|b^xWjx;fu<qFc Ayty1*P*[TRw^e(' );

define( 'NONCE_KEY',        '5q2=mIj;&CyNMGr`VKeK#jL-t;2aS>6C#4-mHW&:ybBHzL.XvjFPfa*wi_Axj@q&' );

define( 'AUTH_SALT',        'Pt([ZwJ`}V^^}Tl xXY!x11!;yPrdQ=Fk)bjv.Ce)JI|RIf~uL#t`>|`h|^6YV:Z' );

define( 'SECURE_AUTH_SALT', '&mGY!Z}r_OHjmb<cFCYS8/2%RBNN7N<zRv_U@l{m^,BdlR zg4QP7YF]dRo#A}o)' );

define( 'LOGGED_IN_SALT',   '< fPB~*KEIkKWHEN:_y5l!Z|N0Ju2zP4<}kZjk<@,d]pQx2g5.Ag#|TXiOm9#^&d' );

define( 'NONCE_SALT',       'w,c/OWOVFs-B~UpgY,9JS(,lvUXBKbLL7abCF?ETeKcXs[tae3c_ %2w8p4c~#[Q' );



/**#@-*/



/**

 * WordPress Database Table prefix.

 *

 * You can have multiple installations in one database if you give each

 * a unique prefix. Only numbers, letters, and underscores please!

 */

$table_prefix = 'as35co_';



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


define( 'W3TC_DYNAMIC_SECURITY', 'SOME_SECURE_STRING_YOU_CREATE' );

/* That's all, stop editing! Happy publishing. */



/** Absolute path to the WordPress directory. */

if ( ! defined( 'ABSPATH' ) ) {

	define( 'ABSPATH', __DIR__ . '/' );

}




/** Sets up WordPress vars and included files. */

require_once ABSPATH . 'wp-settings.php';

