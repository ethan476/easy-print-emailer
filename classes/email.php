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
	private function construct_txt_body($printers_down)
	{
		$number_down = count($printers_down);
		$body = 'There ' . (($number_down == 1) ? 'is' : 'are') . ' currently ' . $number_down . ' printer' . (($number_down == 1) ? ' ' : 's ') . "down.\r\n";

		foreach($printers_down as $printer) {
			$body .= str_replace('Woodlawn - ', '', $printer['display_name']) . ' (<a href="http://' . $printer['ip'] . '">' . $printer['ip'] . '</a>) - ' . $printer['full_status'] . "\r\n";
		}

		$this->txt_body_string = $body;
	}

	/**
	*
	*/
	private function construct_html_body($printers_down)
	{
		$number_down = count($printers_down);
		$body = 'There ' . (($number_down == 1) ? 'is' : 'are') . ' currently '  . $number_down . ' printer' . (($number_down == 1) ? ' ' : 's ') . 'down.';
		
		$body .= '<ol>';

		foreach($printers_down as $printer) {
			$body .= '<li>' . str_replace('Woodlawn - ', '', $printer['display_name']) . ' (<a href="http://' . $printer['ip'] . '">' . $printer['ip'] . '</a>) - ' . $printer['full_status']. '</li>';
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

		$mime = new Mail_mime($this->crlf); 

		/* HTML */
		$html_body = 'Good morning, ' . $itc['first_name'] . '.<br><br>';

		if (!isset($this->html_body_string)) {
			$this->construct_html_body($printers_down);
		}

		$html_body .= $this->html_body_string;

		$mime->setHTMLBody($html_body);


		/* TXT */
		$txt_body = 'Good morning, ' . $itc['first_name'] . '.\r\n\r\n';

		if (!isset($this->txt_body_string)) {
			$this->construct_txt_body($printers_down);
		}

		$txt_body .= $this->txt_body_string;

		$mime->setTXTBody($txt_body);

		return $mime;
	}

	/**
	*
	*/
	public function construct_headers($to)
	{
		$headers = array(
			'To'	=> $to,
			'Subject'	=> $this->smtp_config['subject'],
			'From'	=> $this->smtp_config['from']
		);

		return $headers;
	}

	/**
	*
	*/
	public function send($to, $mime)
	{
		return $this->smtp->send($to, $mime->headers($this->construct_headers($to)), $mime->get());		
	}
}
