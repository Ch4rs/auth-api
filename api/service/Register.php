<?php

include_once '../vendor/autoload.php';

use \Firebase\JWT\JWT;

include_once 'config/cors.php';
include_once  getcwd() . '/config/ConnectionManager.php';

class Manager extends ConnectionManager
{

    public function register($numero,$password,$passw)
    {
        try {
            $cnx = $this->connectSqlSrv();
            $sth = "INSERT INTO tb_usuarios_clientes (numero,password,passw)VALUES('".$numero."','".$password."','".$passw."')";
            $sth = $cnx->prepare($sth);
            $sth->execute();
            if ($sth->rowCount()) {
                http_response_code(200);
                return json_encode(array('message' => 'User Created'));
            }
            else {
                http_response_code(400);
                        return json_encode(array('message' => 'Error Number Exist!'));
            }
        } catch (PDOException $e) {
            http_response_code(400);
            return json_encode(array('message' => 'Internal Server error'));
        }
    }
}
