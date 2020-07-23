<?php
include_once 'config/cors.php';

include_once getcwd() . '/service/Login.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"));
    $obj = new Manager();
    $dt=array('numero'=>$data->numero,'password'=>$data->password);
    echo $obj->login($dt);
}