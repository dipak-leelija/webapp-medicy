<?php



class DatabaseConnection{


    private $servername;
    private $username;
    private $password;
    private $dbname;
    public $conn;


    public function __construct() {

        $this->db_connect();

      }


    function db_connect(){

        $this->servername = 'localhost';
        $this->username = 'root';
        $this->password = '';
        $this->dbname = 'medicy_db';

        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        return $this->conn;

    }



}// DatabaseConnection end



?>