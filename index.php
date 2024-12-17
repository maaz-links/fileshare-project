<?php declare(strict_types=1);
/**
 * PrivateBin
 *
 * a zero-knowledge paste bin
 *
 * @link      https://github.com/PrivateBin/PrivateBin
 * @copyright 2012 Sébastien SAUVAGE (sebsauvage.net)
 * @license   https://www.opensource.org/licenses/zlib-license.php The zlib/libpng License
 */

// change this, if your php files and data is outside of your webservers document root
define('PATH', 'privatebin/');

define('PUBLIC_PATH', __DIR__.'privatebin/');
//var_dump(PATH . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php');exit;
//var_dump('PUBLIC_PATH', __DIR__);exit;
require PATH . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
new PrivateBin\Controller;
