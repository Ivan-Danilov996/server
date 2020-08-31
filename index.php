<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Methods: *');
header('Access-Control-Allow-Credentials: true');
header('Content-type: json/application');
require 'connect.php';
require 'functions.php';

$method = $_SERVER['REQUEST_METHOD'];



$q = $_GET['q'];
$params = explode('/', $q);

$type = $params[0];
$id = $params[1];




if ($method === 'GET') {
    if ($type === 'products') {
        getProducts ($connect);
    } elseif ($type === 'auth') {
        checkAuth($_COOKIE);
    }
} elseif ($method === 'POST') {
    if ($type === 'products') {
        addProduct($connect, $_POST);
    } elseif ($type === 'auth') {
        authorized($connect, $_POST);
    }
} elseif ($method === 'PATCH') {
    if ($type === 'products') {
        if(isset($id)) {
            $data = file_get_contents('php://input');
            $data = json_decode($data, true);
            updateProduct($connect, $id, $data);
        }
    }
} elseif ($method === 'DELETE') {
    if ($type === 'products') {
        if(isset($id)) {
            deleteProduct($connect, $id);
        }
    }
}





