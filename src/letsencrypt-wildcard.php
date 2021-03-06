<?php

$directAdminHost = 'example.com';   // DirectAdmin host (without http:// and endslash)
$directAdminPort = 2222;            // DirectAdmin port (default: 2222)
$directAdminUser = 'joe';           // DirectAdmin username
$directAdminPass = 'mypass123';     // DirectAdmin password

$requestValidationPassword = '';    // CHOOSE A STRONG PASSWORD HERE

error_reporting(E_ALL);
ini_set('display_errors', '1');
 
require_once('httpsocket.php');

// Check if a request validation password was set
if (empty($requestValidationPassword) || trim($requestValidationPassword) == '')
    die ('$requestValidationPassword NOT SET! Script will not run without a password');

if (!isset($_GET['pass']))
    die ('Authentication error');

// Check if pass is valid
if ($requestValidationPassword != $_GET['pass'])
    die ('Authentication error');

if (!isset($_GET['certbot_domain'])) 
    die ('Missing certbot_domain GET parameter');

if (!isset($_GET['certbot_validation'])) 
    die ('Missing certbot_validation GET parameter');

$domain = '_acme-challenge.' . $_GET['certbot_domain'] . '.';
$validationToken = $_GET['certbot_validation'];

$sock = new HTTPSocket;
 
$sock->connect($directAdminHost, $directAdminPort);

$sock->set_login($directAdminUser, $directAdminPass);
$sock->set_method('POST');

// If we're cleaning up, remove the record,
// otherwise add it
if (isset($_GET['cleanup']))
{
    $sock->query('/CMD_API_DNS_CONTROL?domain=' . $_GET['certbot_domain'] . '&action=select&txtrecs0=name=' . $domain . '&value=' . $validationToken);
}
else
{
    $sock->query('/CMD_API_DNS_CONTROL?domain=' . $_GET['certbot_domain'] . '&action=add&type=TXT&name=' . $domain . '&value=' . $validationToken);
}