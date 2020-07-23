<?php
require_once getcwd() . '/config/ConnectionManager.php';
class Manager extends ConnectionManager
{
    /*
    public function get()
    {
        $retval = array(
            'data' => false,
            'error' => false,
            'r' => array('c' => array(), 'd' => array()),
        );

        try {

            $cnx = $this->connectSqlSrv();
            $sth = 'SELECT * FROM tb_detalle_ticket';
            $sth = $cnx->prepare($sth);
            $sth->execute();
            $retval['r']['c'] = array(
                array('data' => 'id_pedido', 'title' => 'id_pedido'), array('data' => 'cantidad', 'title' => 'cantidad'), array('data' => 'fecha', 'title' => 'fecha'), array('data' => 'id_cliente', 'title' => 'id_cliente'), array('data' => 'status', 'title' => 'status'),
                array('data' => 'Actions', 'title' => 'Acciones'),
            );
            while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
                $row['Actions'] = "<button class='btn btn-danger' onclick='deletex(" . $row['id_pedido'] . ")'><i class='far fa-trash-alt'></i></button>
            <button class='btn btn-warning' onclick='editx(" . $row['id_pedido'] . ")'><i class='far fa-edit'></i></button>";
                array_push($retval['r']['d'], $row);
            }
            $retval['data'] = true;
        } catch (PDOException $e) {
            $retval['error'] = true;
            $retval['r'] = $e;
        }
        return json_encode($retval);
    }

    public function consultas($dt)
    {
        $retval = array(
            'data' => false,
            'error' => false,
            'r' => array('d' => array()),
        );
        try {
            $cnx = $this->connectSqlSrv();
            $sth = $cnx->prepare('SELECT *  FROM tb_detalle_ticket WHERE id_pedido=:id_pedido');
            $sth->execute($dt);
            while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
                array_push($retval['r']['d'], $row);
            }
            $retval['data'] = true;
        } catch (PDOException $e) {
            $retval['error'] = true;
            $retval['r'] = $e;
        }
        return json_encode($retval);
    }*/
    public function set($dt)
    {
        $retval = array(
            'data' => false,
            'error' => false,
            'r' => array('d' => array()),
        );
        try {
            $price =$this->get_price($dt['id_producto']);
            $dt['total'] = $dt['cantidad'] * $price;
            if ($this->set_ticket($dt['total'],$dt['id_cliente'])) {
                $dt['id_ticket'] = $this->last_id($dt['id_cliente']);
                $cnx = $this->connectSqlSrv();
                $sth = $cnx->prepare("INSERT INTO tb_detalle_ticket(id_ticket,id_producto,cantidad,total)VALUES (" . $dt['id_ticket'] . "," . $dt['id_producto'] . "," . $dt['cantidad'] . ",'" . $dt['total'] . "')");
                $sth->execute();
                if ($retval['r'] = $sth->rowCount()) {
                    $retval['data'] = true;
                } else {
                    $retval['error'] = true;
                    $retval['r'] = 'Registro Existente';
                }
            }
            else {
                $retval['error'] = true;
                $retval['r'] = 'Error al Realizar Compras';
            }
            
        } catch (PDOException $e) {
            $retval['error'] = true;
            $retval['r'] = $e;
        }
        return json_encode($retval);
    }
    public function last_id($id_cliente)
    {   $id="";
        $cnx = $this->connectSqlSrv();
        $sth = "SELECT MAX(id_ticket) AS id_ticket FROM tb_ticket WHERE id_cliente = ".$id_cliente;
        $sth = $cnx->prepare($sth);
        $sth->execute();
        while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
            $id= $row['id_ticket'];
        }
        return $id;
    }
    public function get_price($id_producto)
    {   $price=0.0;
        $cnx = $this->connectSqlSrv();
        $sth = 'SELECT * FROM tb_producto where id_producto ='.$id_producto;
        $sth = $cnx->prepare($sth);
        $sth->execute();
        while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
            $price= $row['precio'];
        }
        return $price;
    }
    public function set_ticket($total,$id_cliente)
    {   $folio = date("YmdHis");
        $cnx = $this->connectSqlSrv();
        $sth = "INSERT INTO tb_ticket( folio, id_cliente, id_empleado, total) VALUES (".$folio.",".$id_cliente.",1,".$total.")";
        $sth = $cnx->prepare($sth);
        $sth->execute();
        if ($sth->rowCount()) {
            return true;
        }
        return false;
    }
    /*
    public function delete($dt)
    {
        $retval = array(
            'data' => false,
            'error' => false,
            'r' => array('d' => array()),
        );
        try {
            $cnx = $this->connectSqlSrv();
            $sth = $cnx->prepare('DELETE FROM tb_detalle_ticket WHERE id_pedido=:id_pedido');
            $sth->execute($dt);
            if ($retval['r'] = $sth->rowCount()) {
                $retval['data'] = true;
            }
        } catch (PDOException $e) {
            $retval['error'] = true;
            $retval['r'] = $e;
        }
        return json_encode($retval);
    }
    public function update($dt)
    {
        $retval = array(
            'data' => false,
            'error' => false,
            'r' => array('d' => array()),
        );
        try {

            $cnx = $this->connectSqlSrv();
            $sth = $cnx->prepare('UPDATE tb_detalle_ticket SET cantidad=:cantidad,fecha=:fecha,id_cliente=:id_cliente,status=:status  WHERE id_pedido=:id_pedido');
            $sth->execute($dt);
            if ($retval['r'] = $sth->rowCount()) {
                $retval['data'] = true;
            }
        } catch (PDOException $e) {
            $retval['error'] = true;
            $retval['r'] = $e;
        }
        return json_encode($retval);
    }
    public function getselect($dt)
    {
        $query = $this->setQuery($dt);
        $retval = array(
            'data' => false,
            'error' => false,
            'r' => array('c' => array(), 'd' => array()),
        );
        try {
            $cnx = $this->connectSqlSrv();
            $sth = $cnx->prepare($query['query']);
            $sth->execute();
            $retval['r']['c'] = array(
                array('data' => $query['id'], 'title' => $query['nombre']),
            );
            while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
                array_push($retval['r']['d'], $row);
            }
            $retval['data'] = true;
        } catch (PDOException $e) {
            $retval['error'] = true;
            $retval['r'] = $e;
        }
        return json_encode($retval);
    }
    public function setQuery($variable)
    {
        $retval = array(
            'id' => '',
            'nombre' => '',
            'query' => '',
        );
        switch ($variable) {

            default:
                break;
        }
    }*/
}