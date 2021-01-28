<?php

include 'php/config.php';
include 'php/db.class.php';
include 'php/burger.class.php';

ini_set('display_errors', 'on');
ini_set('error_reporting', E_NOTICE|E_ALL);

$name = $_POST['name'];
$email = $_POST['email'];
$fields = ['street', 'home'];
$address = '';
foreach ($_POST as $item => $value) {
    if ($value && in_array($item, $fields)) {
        $address .= $value;
    }
}

$burger = new Burger();
$user = $burger->getUserByEmail();
if ($user) {
     $burger->incOrders($user['id']);
    $ordersCount = $user['ordersCount'] + 1;
}else {
    $orderNumber = 1;
    $user = $burger->createUser();

}

$orderNumber = $burger->addOrder($user['id']);

echo "Ваш заказ получен, он будет доставлен по адрессу $address<br>
Номер ващего заказа $orderNumber.<br>
Это ваш $ordersCount-ый заказ.";

