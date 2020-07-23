<?php

include_once '../vendor/autoload.php';

use \Firebase\JWT\JWT;

include_once 'config/cors.php';
include_once  getcwd() . '/config/ConnectionManager.php';

class Manager extends ConnectionManager
{

    /*public function get()
    {
        $retval = array();
        try {
            $cnx = $this->connectSqlSrv();
            $sth = "SELECT *  FROM tb_usuarios tu WHERE activo = 1" ;
            $sth = $cnx->prepare($sth);
            $sth->execute();
            if ($sth->rowCount()) {
                while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
                    array_push($retval,$row);
                }
                http_response_code(200);
                return json_encode($retval);
            } else {
                http_response_code(400);
                return json_encode(array('message' => 'NO HAY DATOS'));
            }
        } catch (PDOException $e) {
            http_response_code(400);
            return json_encode(array('message' => 'ERROR DE CONECCION'));
        }   
    }
    public function get_usuario($id)
    {
        $retval = array();
        try {
            $cnx = $this->connectSqlSrv();
            $sth = "SELECT *  FROM tb_usuarios tu WHERE activo = 1 and id_usuario = " .$id;
            $sth = $cnx->prepare($sth);
            $sth->execute();
            if ($sth->rowCount()) {
                while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
                    array_push($retval,$row);
                }
                http_response_code(200);
                return json_encode($retval);
            } else {
                http_response_code(400);
                return json_encode(array('message' => 'NO HAY DATOS'));
            }
        } catch (PDOException $e) {
            http_response_code(400);
            return json_encode(array('message' => 'ERROR DE CONECCION'));
        }   
    }*/
    public function login($dt)
    {
        try {
            $cnx = $this->connectSqlSrv();
            $sth = "SELECT *  FROM tb_usuarios_clientes tu WHERE numero = '" . $dt['numero'] . "' AND activo = 1;";
            $sth = $cnx->prepare($sth);
            $sth->execute();
            if ($sth->rowCount()) {
                while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
                    if (password_verify($dt['password'], $row['password'])) {
                        $key = "YOUR_SECRET_KEY";  // JWT KEY
                        $payload = array(
                            'id_usuario' => $row['id_usuario'],
                            'id_cliente' => $row['id_cliente'],
                            'token' => '',
                            'numero' => $row['numero'],
                        );
                        $token = JWT::encode($payload, $key);
                        $payload['token']=$token;
                        http_response_code(200);
                        return json_encode($payload);
                    } else {
                        http_response_code(400);
                        return json_encode(array('message' => 'Login Failed!'));
                    }
                }
            }
        } catch (PDOException $e) {
            http_response_code(400);
            return json_encode(array('message' => 'Login Failed!'));
        }
    }
}
