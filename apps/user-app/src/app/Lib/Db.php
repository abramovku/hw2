<?php
namespace App\Lib;

class Db
{
    public $result;
    private $logger;
    private $stm, $dbh;
    private $db_name;
    private $db_uri;
    private $username;
    private $password;

    private static $instances = [];

    protected function __construct() {
        $this->logger = Logger::getInstance();
        $this->db_uri = getenv('DATABASE_URI');
        $this->db_name = getenv('POSTGRES_DB');
        $this->username = getenv('POSTGRES_USER');
        $this->password = getenv('POSTGRES_PASSWORD');
        $this->connectToDB();
    }

    public static function getInstance(): Db
    {
        $cls = static::class;
        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new static();
        }

        return self::$instances[$cls];
    }
    
    private function connectToDB()
    {
        try {
            $this->dbh = new \PDO($this->db_uri, $this->username, $this->password,
             [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
	        $this->dbh->exec("SET CLIENT_ENCODING TO UTF8;");
        }
        catch (\PDOException $e) {
            $this->logger->info($e->getMessage());
            throw $e;
        }

        return ($this->dbh);
    }
    
    public function query($sql)
    {
        try {
            $this->result = $this->dbh->exec($sql);
            return $this->result;
        }
        catch (\PDOException $e) {
            $this->logger->info($e->getMessage());
            throw $e;
        }
    }
    
    public function last_insert_id()
    {
        try {
            $this->result = $this->dbh->lastInsertId();
            return $this->result;
        }
        catch (\PDOException $e) {
            $this->logger->info($e->getMessage());
            throw $e;
        }
    }

    public function fetchQuery($sql)
    {
        try {
            $this->stm = $this->dbh->query($sql);
            $this->result = $this->stm->fetchAll(\PDO::FETCH_ASSOC);
            return $this->result;
        }
        catch (\PDOException $e) {
            $this->logger->info($e->getMessage());
            throw $e;
        }
    }
    
    public function queryPrepared($sql, $vars)
    {
        try {
            $this->result = $this->dbh->prepare($sql) or die("QUERY FAILED !!! " . $sql);
            $this->result->execute($vars);
            // only for returning value #TODO check for rowcount();
            $this->result = $this->result->fetchAll(\PDO::FETCH_ASSOC); 
                return $this->result;
        }
        catch (\PDOException $e) {
            $this->logger->info($e->getMessage());
            throw $e;
        }
    }
    
    public function fetchQueryPrepared($sql, $vars)
    {
        try {
            $this->stm = $this->dbh->prepare($sql) or die("QUERY FAILED !!! " . $sql);
            $this->stm->execute($vars);
            $this->result = $this->stm->fetchAll(\PDO::FETCH_ASSOC);
            
            return $this->result;
            $this->dbh = null;
        }
        catch (\PDOException $e) {
            $this->logger->info($e->getMessage());
            throw $e;
        }
    }
}