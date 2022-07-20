<?php

/**
 * Configuration
 *
 * For more info about constants please @see http://php.net/manual/en/function.define.php
 */

/**
 * Configuration for: Error reporting
 * Useful to show every little problem during development, but only show hard errors in production
 */
session_start();
define('ENVIRONMENT', 'development');

if (ENVIRONMENT == 'development' || ENVIRONMENT == 'dev') {
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
}
function get_base_url()
{

//    $protocol = filter_input(INPUT_SERVER, 'HTTPS');
//    var_dump($protocol);
//    if (empty($protocol)) {
        $protocol = "https";
 //   }

    $host = filter_input(INPUT_SERVER, 'HTTP_HOST');

    $request_uri_full = filter_input(INPUT_SERVER, 'REQUEST_URI');
    $last_slash_pos = strrpos($request_uri_full, "/");
    if ($last_slash_pos === FALSE) {
        $request_uri_sub = $request_uri_full;
    } else {
        $request_uri_sub = substr($request_uri_full, 0, $last_slash_pos + 1);
    }

    return $protocol . "://" . $host ;

}

/**
 * Configuration for: URL
 * Here we auto-detect your applications URL and the potential sub-folder. Works perfectly on most servers and in local
 * development environments (like WAMP, MAMP, etc.). Don't touch this unless you know what you do.
 *
 * URL_PUBLIC_FOLDER:
 * The folder that is visible to public, users will only have access to that folder so nobody can have a look into
 * "/application" or other folder inside your application or call any other .php file than index.php inside "/public".
 *
 * URL_PROTOCOL:
 * The protocol. Don't change unless you know exactly what you do. This defines the protocol part of the URL, in older
 * versions of MINI it was 'http://' for normal HTTP and 'https://' if you have a HTTPS site for sure. Now the
 * protocol-independent '//' is used, which auto-recognized the protocol.
 *
 * URL_DOMAIN:
 * The domain. Don't change unless you know exactly what you do.
 * If your project runs with http and https, change to '//'
 *
 * URL_SUB_FOLDER:
 * The sub-folder. Leave it like it is, even if you don't use a sub-folder (then this will be just "/").
 *
 * URL:
 * The final, auto-detected URL (build via the segments above). If you don't want to use auto-detection,
 * then replace this line with full URL (and sub-folder) and a trailing slash.
 */

define('URL_PUBLIC_FOLDER', 'public');
define('URL_PROTOCOL', '//');
define('URL_DOMAIN', $_SERVER['HTTP_HOST']);
define('URL_SUB_FOLDER', str_replace(URL_PUBLIC_FOLDER, '', dirname($_SERVER['SCRIPT_NAME'])));
define('URL', URL_PROTOCOL . URL_DOMAIN . URL_SUB_FOLDER);
define('PATH', URL_PROTOCOL . URL_DOMAIN);
define('ROOT_PATH', get_base_url());
/**
 * Configuration for: Database
 * This is the place where you define your database credentials, database type etc.
 */
define('DB_TYPE', 'mysql');
define('DB_HOST', 'localhost');
define('DB_NAME', 'tv');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');
define("EMAIL", "");
define("EMAIL_USER", "");
define("EMAIL_PASSWORD", "");
define("EMAIL_HOST", "");
define("LOGO", "/img/mbox.png");
define("WHITE_LOGO", "img/mbox.png");
define("BUSSINES", "MBOX STUDIOS <br> SUCCESSFUL MEDIA PARTNER");
define("SERVER", "https://www.playlist.bg/");


