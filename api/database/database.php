<?php
class Database{

	private $database;
	private $host;
	private $db_name;
	private $username;
	private $password;
	private $port;

	public function __construct(){
		$this->database = parse_url(getenv('DATABASE_URL'));
		//$this->database = parse_url("postgres://csemebliiqxwjb:7d14b5bfb169022b730f073d3a6f760c0154c145d61b186dcfc57d1f327c8e64@ec2-54-243-54-6.compute-1.amazonaws.com:5432/dludt50ava90k");
		//$this->database = parse_url('http://postgres:postgres@127.0.0.1:5432/api_test');
		// specify your own database credentials
		$this->host = $this->database['host'];
		$this->db_name = ltrim($this->database['path'], '/');
		$this->username = $this->database['user'];
		$this->password = $this->database['pass'];
		$this->port = $this->database['port'];
	}
	public $conn;

	// get the database connection
	public function getConnection(){

		$this->conn = null;

		try{
			$this->conn = new PDO("pgsql:host=" . $this->host . "; port=" . $this->port . ";dbname=" . $this->db_name, $this->username, $this->password);
			$this->conn->exec("set names utf8");
		}catch(PDOException $exception){
			echo "Connection error: " . $exception->getMessage();
		}

		return $this->conn;
	}
}
?>
