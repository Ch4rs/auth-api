<?php
include_once getcwd() . '/service/Register.php';
include_once 'config/cors.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    $numero = $data->numero;
    $passw = $data->password;


    // Hash Password
    $password = password_hash($passw, PASSWORD_DEFAULT);

    // U can do validation like unique username etc....
    $obj = new Manager();
    echo $obj->register($numero,$password,$passw);
    
} else {
    http_response_code(404);
}