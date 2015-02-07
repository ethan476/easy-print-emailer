<?php

require_once "Mail.php";
require_once "Mail/mime.php";

class Email
{
	/**
	*
	*/
	private $crlf = "\r\n";

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

	/*
	*
	*/
	private function construct_txt_body()
	{
		$body .= 'There ' . (($number_down == 1) ? 'is ' : 'are ') . $number_down . ' printer' . (($number_down == 1) ? ' ' : 's ') . 'down.';

		foreach($printers_down as $printer) {
			$body .= $printer['cups_name'] . ' (<a href="http://' . $printer['ip'] . '">' . $printer['ip'] . '</a>)\r\n';
		}

		$this->txt_body_string = $body;
	}

	/**
	*
	*/
	private function construct_html_body()
	{
		$body .= 'There ' . (($number_down == 1) ? 'is ' : 'are ') . $number_down . ' printer' . (($number_down == 1) ? ' ' : 's ') . 'down.';
		
		$body .= '<ol>';

		foreach($printers_down as $printer) {
			$body .= '<li>' . $printer['cups_name'] . ' (<a href="http://' . $printer['ip'] . '">' . $printer['ip'] . '</a>)' . '</li>';
		}

		$body .= '</ol>';

		$this->html_body_string = $body;
	}

	/**
	*
	*/
	public function construct_body($itc, $printers_down)
	{
		$this->smtp_config['subject'] = 'Good Morning, ' . $itc['first_name'];
		$number_down = count($printers_down);


		$mime = new Mail_mime($this->crlf); 

		/* HTML */
		$html_body = 'Good morning, ' . $itc['first_name'] . '.<br><br>';

		if (!isset($this->html_body_string)) {
			$this->construct_html_body();
		}

		$html_body .= $this->html_body_string;

		$mime->setHTMLBody($html_body);


		/* TXT */
		$txt_body = 'Good morning, ' . $itc['first_name'] . '.\r\n\r\n';

		if (!isset($this->txt_body_string)) {
			$this->construct_txt_body();
		}

		$txt_body .= $this->txt_body_string;

		$mime->setTXTBody($txt_body);
	}

	/**
	*
	*/
	public function construct_headers($to)
	{
		$headers = array(
			'To'	=> $to,
			'Subject'	=> $this->smtp_config['subject'];
			'From'	=> $this->smtp_config['from']
		);

		return $headers;
	}

	/**
	*
	*/
	public function send($to, $mime)
	{
		$mail = $this->smtp->send($to, $mime->headers($this->construct_headers($to)), $mime->get());		
		
		if (PEAR::isError($mail)) {
			echo $mail->getMessage() . '\n';
		} else {
			echo 'Successfully sent Easy Print report to \'' . $to . "\'\n";
		}
	}
}
