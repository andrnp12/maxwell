<?php
class DBConnection
{

    private string $host;
    private string $username;
    private string $password;
    private string $database;

    public mysqli $conn;


    public function __construct()
    {

        $envFilePath = __DIR__ . '/../.env';

        if (file_exists($envFilePath)) {
            $env = parse_ini_file($envFilePath);

            $this->host = $env['DB_SERVER'];
            $this->username = $env['DB_USERNAME'];
            $this->password = $env['DB_PASSWORD'];
            $this->database = $env['DB_NAME'];
        } else {
            die("File .env tidak ditemukan!");
        }

        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database);


        if (!$this->conn) {
            echo 'Cannot connect to database server';
            exit;
        }
    }
    public function __destruct()
    {
        $this->conn->close();
    }
}
