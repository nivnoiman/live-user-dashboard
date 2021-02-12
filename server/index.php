<?php
define( 'ABSPATH', dirname( __FILE__, 1 ) );

include( 'system/init.php' );

$controller = new Api_Controller();
$controller->process_request();
