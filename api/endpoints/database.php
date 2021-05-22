<?php

    class Database {

        private $host = "localhost";
        private $database = "testeNewm";
        private $username = "root";
        private $password = "compfuture";

        public $conn;

        public function getConnection() {

            $conn = null;
            
            try {
                $conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->database, $this->username, $this->password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch(PDOException $exception) {
                echo "Não foi possível se comunicar com o banco de dados -> " . $exception->getMessage();
            }
            return $conn;
        }
    }

?>