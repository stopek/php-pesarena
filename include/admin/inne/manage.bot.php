<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_error', 0);

require_once 'include/admin/funkcje/phplibgadu.php';

$number = GG_STRONY;
$password = GG_HASLO;
$recipient = 9855119;
$msg = 'test testem testem przekladany testowany z testem potestowwany 
test testem testem przekladany testowany z testem potestowwany test 
testem testem przekladany testowany z testem potestowwany test testem
 testem przekladany testowany z testem potestowwanytest testem testem przekladany testowany z testem potestowwany 
test testem testem przekladany testowany z testem potestowwany test 
testem testem przekladany testowany z testem potestowwany test testem
 testem przekladany testowany z testem potestowwanytest testem testem przekladany testowany z testem potestowwany 
test testem testem przekladany testowany z testem potestowwany test 
testem testem przekladany testowany z testem potestowwany test testem
 testem przekladany testowany z testem potestowwanytest testem testem przekladany testowany z testem potestowwany 
test testem testem przekladany testowany z testem potestowwany test 
testem testem przekladany testowany z testem potestowwany test testem
 testem przekladany testowany z testem potestowwanytest testem testem przekladany testowany z testem potestowwany 
test testem testem przekladany testowany z testem potestowwany test 
testem testem przekladany testowany z testem potestowwany test testem
 testem przekladany testowany z testem potestowwanytest testem testem przekladany testowany z testem potestowwany 
test testem testem przekladany testowany z testem potestowwany test 
testem testem przekladany testowany z testem potestowwany test testem
 testem przekladany testowany z testem potestowwanytest testem testem przekladany testowany z testem potestowwany 
test testem testem przekladany testowany z testem potestowwany test 
testem testem przekladany testowany z testem potestowwany test testem
 testem przekladany testowany z testem potestowwany ';

$gg = new GG;

$gg->debug_level = 255 & ~GG::DEBUG_HTTP;

$p = array();
$p['uin'] = $number;
$p['password'] = $password;

if (!$gg->login($p)) {
    return;
}

printf("Connected.\n");

$gg->notify();

$gg->send_message($recipient, $msg);
$gg->send_message($recipient, $msg);
$gg->send_message($recipient, $msg);
$gg->send_message($recipient, $msg);
$gg->send_message($recipient, $msg);
$gg->send_message($recipient, $msg);
$gg->send_message($recipient, $msg);
$gg->send_message($recipient, $msg);
$gg->send_message($recipient, $msg);
$gg->send_message($recipient, $msg);
$gg->send_message($recipient, $msg);
$gg->send_message($recipient, $msg);
$gg->send_message($recipient, $msg);
$gg->send_message($recipient, $msg);
$gg->send_message($recipient, $msg);
$gg->send_message($recipient, $msg);
$gg->send_message($recipient, $msg);


$gg->logout();
?>