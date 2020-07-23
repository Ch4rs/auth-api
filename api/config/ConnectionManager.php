<?php

abstract class ConnectionManager
{
    protected function connectSqlSrv()
    {
        try {
            $hostname = "192.168.0.200";
            $dbname = "recompensas";
            $username = "mlegnaco";
            $pw = 'mlegna2020';
            return $pdo = new PDO ("mysql:host=$hostname;dbname=$dbname","$username","$pw");
          } catch (PDOException $e) {
            echo "Failed to get DB handle: " . $e->getMessage() . "\n";
            exit;
          }
    }
}
?>