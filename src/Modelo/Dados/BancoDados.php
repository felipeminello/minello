<?php
namespace Modelo\Dados;

use \PDO;

abstract class BancoDados {
	# @object, The PDO object
	protected $pdo;

	# @object, PDO statement object
	protected $sQuery;

	# @array,  The database settings
	protected $settings;

	# @bool ,  Connected to the database
	protected $bConnected = false;

	# @object, Object for logging exceptions	
	protected $log;

	# @array, The parameters of the SQL query
	protected $parameters;
	
	
	public function __construct() {
		$this->Connect();
	}
	
	private function Connect() {
		$dsn = 'mysql:dbname=minello;host=localhost';
		try {
			# Read settings from INI file, set UTF8
			$this->pdo = new PDO($dsn, 'root', 'camarao', array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
			
			# We can now log any exceptions on Fatal error. 
			$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			# Disable emulation of prepared statements, use REAL prepared statements instead.
			$this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
			
			# Connection succeeded, set the boolean to true.
			$this->bConnected = true;
		} catch (PDOException $e) {
			# Write into log
			echo ('Erro: '.$e->getMessage());
			die();
		}
	}
	
	public function CloseConnection()
 	{
 		# Set the PDO object to null to close the connection
 		# http://www.php.net/manual/en/pdo.connections.php
 		$this->pdo = null;
 	}
 	
	private function Init($query,$parameters = "") {
		# Connect to database
		if(!$this->bConnected) { $this->Connect(); }
		try {
			# Prepare query
			$this->sQuery = $this->pdo->prepare($query);
			
			# Add parameters to the parameter array	
			$this->bindMore($parameters);

			# Bind parameters
			if(!empty($this->parameters)) {
				foreach($this->parameters as $param)
				{
					$parameters = explode("\x7F",$param);
					$this->sQuery->bindParam($parameters[0],$parameters[1]);
				}		
			}

			# Execute SQL 
			$this->succes 	= $this->sQuery->execute();		
		} catch(PDOException $e) {
			# Write into log and display Exception
			echo ($e->getMessage());
			die();
		}

		# Reset the parameters
		$this->parameters = array();
	}
	
	public function bind($para, $value) {	
		$this->parameters[sizeof($this->parameters)] = ":" . $para . "\x7F" . utf8_encode($value);
	}
	
	public function bindMore($parray) {
		if(empty($this->parameters) && is_array($parray)) {
			$columns = array_keys($parray);
			foreach($columns as $i => &$column)	{
				$this->bind($column, $parray[$column]);
			}
		}
	}
	
	
	public function query($query,$params = null, $fetchmode = PDO::FETCH_ASSOC) {
		$query = trim($query);

		$this->Init($query,$params);

		$rawStatement = explode(" ", $query);
		
		# Which SQL statement is used 
		$statement = strtolower($rawStatement[0]);
		
		if ($statement === 'select' || $statement === 'show') {
			return $this->sQuery->fetchAll($fetchmode);
		} elseif ( $statement === 'insert' ||  $statement === 'update' || $statement === 'delete' ) {
			return $this->sQuery->rowCount();	
		} else {
			return NULL;
		}
	}
	
}