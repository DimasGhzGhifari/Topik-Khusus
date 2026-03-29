<?php
require 'vendor/autoload.php';
$ok = class_exists('PhpAmqpLib\Connection\AMQPStreamConnection');
echo $ok ? "OK" : "FAIL";
