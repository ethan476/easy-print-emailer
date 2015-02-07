<?php

/**
*
*/
class DB
{
	/**
	*
	*/
	private $pdo = NULL;

	/**
	*
	*/
	private $config = NULL;

	/**
	*
	*/
	public function __construct()
	{
		$this->config = require('db_config.php'); 

		$this->pdo = new PDO($this->construct_dsn(), $this->config['user'], $this->config['pass']);
	}

	/**
	*
	*/
	private function construct_dsn()
	{
		return 'mysql:host=' . $this->config['host'] . ';port=' . $this->config['port'] . ';dbname=' . $this->config['db'] . ';charset=utf8';  
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
	public function get_itcs()
	{
		try {
			$stmt = $this->pdo->query("SELECT * FROM `itcs`");
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch(PDOException $ex) {
			echo $ex->getMessage() . '\n';
			exit(1);
		}
	}

	/**
	*
	*/
	public function get_down_printers()
	{
		try {
			$stmt = $this->pdo->query("SELECT * FROM `printers` WHERE `status` = 2 AND `emailable` = 1");
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch(PDOException $ex) {
			echo $ex->getMessage() . '\n';
			exit(1);
		}
	}
}
