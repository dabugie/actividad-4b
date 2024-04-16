<?php
class Database
{
    private $hostname = "localhost";
    private $database = "cadapestore";
    private $username = "cadaperoot";
    private $password = "cadaperoot";
    private $charset = "utf8";

    function conectar()
    {
        try {
            $conexion = "mysql:host=" . $this->hostname . "; dbname=" . $this->database . "; charset=" . $this->charset;

            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false
            ];

            $pdo = new PDO($conexion, $this->username, $this->password, $options);

            return $pdo;
        } catch (PDOException $e) {
            echo "Error en la conexion: " . $e->getMessage();
            exit;
        }
    }
}
