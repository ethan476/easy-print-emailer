<?php

require_once "Mail.php";

class Email
{
	/**
	*
	*/
	private $stmp_config = NULL;

	/**
	*
	*/
	private $smtp = NULL;

	/**
	*
	*/
	public function __construct()
	{
		$this->smtp_config = require('smtp_config.php');

		$this->smtp = Mail::factory('smtp', $this->smtp_config['factory']);
	}

	/**
	*
	*/
	public function __destruct()
	{

	}

	/**
	*
	*/
	public function construct_body($itc, $printers_down)
	{
		return "Message TBD...\r\n-Easy Print Service";
	}

	/**
	*
	*/
	public function construct_headers($to)
	{
		$headers = array(
			'To'	=> $to,
			'From'	=> $this->smtp_config['from']
		);

		return $headers;
	}

	/**
	*
	*/
	public function send($to, $body)
	{
		$mail = $this->smtp->send($to, $this->construct_headers($to), $body);		
		
		if (PEAR::isError($mail)) {
			echo $mail->getMessage() . '\n';
		} else {
			echo 'Successfully sent Easy Print report to \'' . $to . "\'\n";
		}
	}
}
