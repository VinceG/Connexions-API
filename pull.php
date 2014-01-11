<?php

error_reporting(-1);
ini_set('display_errors', true);

require_once('vendor/autoload.php');
require_once('Connexions.php');

$login = '';
$token = '';

$obj = new Connexions($login, $token);

$req = $obj->getUpdatedAppraisal('0000');
$obj->debug();