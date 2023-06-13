<?php
/**
 * Import ENV settings for Docker containers.
 *   ln -s config.docker.php config.php
 */

function file_env($var, $default) {
    $env_filename = getenv($var.'_FILE');

    if ($env_filename===false) {
        return getenv($var) ?: $default;
        } elseif (is_readable($env_filename)) {
        return trim(file_get_contents($env_filename), "\n\r");
    } else {
        // no i10n, gettext not yet loaded
        error_log("$var:$env_filename can not be read");
        return $default;
    }
}

/**
 * Path to access phpipam in site URL, http:/url/BASE/
 * If not defined it will be discovered and set automatically.
 *
 * BASE definition should end with a trailing slash "/"
 * Examples:
 *
 *  If you access the login page at http://company.website/         =  define('BASE', "/");
 *  If you access the login page at http://company.website/phpipam/ =  define('BASE', "/phpipam/");
 *  If you access the login page at http://company.website/ipam/    =  define('BASE', "/ipam/");
 *
 */

getenv('IPAM_BASE') ? define('BASE', getenv('IPAM_BASE')) : false;

/**
 * Import default values
 */
require('config.dist.php');

/**
 * database connection details
 ******************************/
$db['host']    = file_env('IPAM_DATABASE_HOST',    $db['host']);
$db['user']    = file_env('IPAM_DATABASE_USER',    $db['user']);
$db['pass']    = file_env('IPAM_DATABASE_PASS',    $db['pass']);
$db['name']    = file_env('IPAM_DATABASE_NAME',    $db['name']);
$db['port']    = file_env('IPAM_DATABASE_PORT',    $db['port']);
$db['webhost'] = file_env('IPAM_DATABASE_WEBHOST', $db['webhost']);

/**
 * - "None" requires HTTPS (implies "Secure;")
 */
$cookie_samesite = file_env('COOKIE_SAMESITE', $cookie_samesite);

/**
 * Session storage - files or database
 *
 * @var string
 */
$session_storage                =   "database";
$allow_untested_php_versions    =   true;
$db['ssl']                      =   true;
$db['ssl_ca'] = '/phpipam/TrustRoot.crt.pem';
$db['ssl_verify'] = 'false';
