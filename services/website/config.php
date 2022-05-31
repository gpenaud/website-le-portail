<?php
/**
 * @package Dotclear
 *
 * @copyright Olivier Meunier & Association Dotclear
 * @copyright GPL-2.0-only
 */

if (!defined('DC_RC_PATH')) {return;}

// Database driver (mysql (deprecated, disabled in PHP7), mysqli, mysqlimb4 (full UTF-8), pgsql, sqlite)
define('DC_DBDRIVER','mysqli');

// Database hostname (usually "localhost")
define('DC_DBHOST','mysql');

// Database user
define('DC_DBUSER','docker');

// Database password
define('DC_DBPASSWORD','docker');

// Database name
define('DC_DBNAME','leportaivfgam');

// Tables' prefix
define('DC_DBPREFIX','dcgamv3_');

// Persistent database connection
define('DC_DBPERSIST', false);

// Crypt key (password storage)
define('DC_MASTER_KEY','13722d8a0581e403bd1b6a10fbb9336a');

// Admin URL. You need to set it for some features.
define('DC_ADMIN_URL','https://admin.leportail.org/');

// Admin mail from address. For password recovery and such.
define('DC_ADMIN_MAILFROM','webmaster@leportail.org');

// Cookie's name
define('DC_SESSION_NAME', 'dcxd');

// Plugins root
define('DC_PLUGINS_ROOT',dirname(__FILE__).'/../plugins'.PATH_SEPARATOR.dirname(__FILE__).'/../../all-blogs/plugins');

// Template cache directory
define('DC_TPL_CACHE', path::real(dirname(__FILE__) . '/..') . '/cache');

// Var directory
define('DC_VAR', path::real(dirname(__FILE__) . '/..') . '/var');

// Cryptographic algorithm
define('DC_CRYPT_ALGO', 'sha512');

// For cryptography
define('DC_VENDOR_NAME', "website-leportail");

// force admin context
define('DC_CONTEXT_ADMIN', true);
