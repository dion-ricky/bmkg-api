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
