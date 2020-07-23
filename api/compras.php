<?php
include_once 'config/cors.php';

include_once getcwd() . '/service/Compras.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"));
    $obj = new Manager();
    $dt=array('id_producto'=>$data->id_producto,'cantidad'=>$data->cantidad,'id_cliente'=>$data->id_cliente);
    echo $obj->set($dt);
}