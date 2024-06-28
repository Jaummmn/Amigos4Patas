<?php
abstract class Connection
{
    private static $conn;

    public static function getConn()
    {
        if (self::$conn == null) {
            try {
                $host = 'localhost';
                $dbname = '---';
                $username = '---';
                $password = '---';

                self::$conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                // Tratar erros de conexão aqui
                die("Connection failed: " . $e->getMessage());
            }
        }
        
        return self::$conn;
    }
}
