<?php

function getProducts ($connect) {
    $products = mysqli_query($connect, "SELECT * FROM `products`");

    $productsList = [];
    
    while($product = mysqli_fetch_assoc($products)) {
        $productsList[] = $product;
    }
    
    echo json_encode($productsList);
}

function addProduct ($connect, $data) {
    $postId = $data["prodId"];
    $postName = $data["name"];
    $postPrice = $data["price"];
    $postQuantity = $data["quantity"];
    $postDate = $data["date"];

        if (!validation ($postName, $postPrice, $postQuantity, $postDate)) {
            mysqli_query($connect, "INSERT INTO `products` (`name`,`price`,`quantity`,`date`, `prodId`) VALUES ('$postName','$postPrice','$postQuantity','$postDate', '$postId')");
            http_response_code(201);
            $res = [
                "status" => true,
                "id" => mysqli_insert_id($connect)
            ];
            echo json_encode($res);
        } else {
            echo validation($postName, $postPrice, $postQuantity, $postDate);
        }
}

function updateProduct($connect, $id, $data) {
    $postName = $data["name"];
    $postPrice = $data["price"];
    $postQuantity = $data["quantity"];
    $postDate = $data["date"];

    if (!validation ($postName, $postPrice, $postQuantity, $postDate)) {
        mysqli_query($connect, "UPDATE `products` SET name = '$postName',price = '$postPrice', quantity = '$postQuantity',date = '$postDate' WHERE id = '$id'");
        http_response_code(200);
        $res = [
            "status" => true,
            "message" => "product is updated"
        ];
        echo json_encode($res);
    } else {
        echo validation($postName, $postPrice, $postQuantity, $postDate);
    }
}

function deleteProduct($connect, $id) {
    mysqli_query($connect, "DELETE FROM `products` WHERE id = '$id'");
    http_response_code(200);
        $res = [
            "status" => true,
            "message" => "product is deleted"
        ];
        echo json_encode($res);
}

function checkAuth($cookie) {
    if (isset($cookie['isAuthorized'])) {
        echo json_encode(array('isAuthorized'=> true));
    } else {
        echo json_encode(array('isAuthorized'=> false));
    }
}

function authorized($connect, $data) {
    $postValue = $data["text"];
    $result = mysqli_query($connect, "SELECT * FROM `users` WHERE `password` = '".md5($postValue)."' ");

    if (printResult ($result)) {
        echo json_encode(array('isAuthorized'=> true));
    } else {
        http_response_code(422);
    }
}

function printResult ($result) {
    while (($row = $result->fetch_assoc ()) != false) {
        return json_encode($row);
    }
}

function validation($postName, $postPrice, $postQuantity, $postDate) {
    if (!$postName || !$postPrice || !$postQuantity || !$postDate) {
        $res = [
            "status" => false,
            "message" => 'empty line',
        ];
        $result = json_encode($res);
        return $result;
    } elseif (!is_numeric($postPrice)) {
        $res = [
            "status" => false,
            "message" => 'invalid price value',
        ];
        $result = json_encode($res);
        return $result;
    } elseif (!is_numeric($postQuantity)) {
        $res = [
            "status" => false,
            "message" => 'invalid quantity value',
        ];
        $result = json_encode($res);
        return $result;
    } else {
        $result = false;
        return $result;
    }
}