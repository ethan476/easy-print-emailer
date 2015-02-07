<?php

require 'classes/db.php';
require 'classes/email.php';

$db = new DB();
$email = new Email();

$down_printers = $db->get_down_printers();

$itcs = $db->get_itcs();

foreach($itcs as $itc) {
	$email->send($itc['email'], $email->construct_body($itc, $down_printers));
}
