<?php

require 'classes/db.php';
require 'classes/email.php';

$db = new DB();
$email = new Email();

$down_printers = $db->get_down_printers();

$itcs = $db->get_itcs();

$number_of_itcs = count($itcs);
$i = 0;

for($i = 0; $i < $number_of_itcs; $i++) {
	$mail = $email->send($itcs[$i]['email'], $email->construct_body($itcs[$i], $down_printers));

	if (PEAR::isError($mail)) {
		//echo $mail->getMessage() . "\n";
		echo "Failed to send Easy Print Report to: '" . $itcs[$i]['email'] . "' (" . ($i + 1) . "/" . $number_of_itcs . ")\n";
	} else {
		echo "Successfully sent Easy Print Report to: '" . $itcs[$i]['email'] . "' (" . ($i + 1) . "/" . $number_of_itcs . ")\n";
	}
}
