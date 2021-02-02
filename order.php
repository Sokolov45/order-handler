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
        $address .= $value . " ";
    }
}

$burger = new Burger();
$user = $burger->getUserByEmail($email);
if ($user) {
    $userId = $user['id'];
    $burger->incOrders($userId);
    $ordersCount = $user['orders_count'] + 1;
}else {
    $ordersCount = 1;
    $userId = $burger->createUser($name, $email);
}

$orderNumber = $burger->addOrder($userId, $address);

echo "Ваш заказ получен, он будет доставлен по адрессу $address<br>
Номер ващего заказа $orderNumber.<br>
Это ваш $ordersCount-ый заказ.";

$db = Db::getInstance();
$db->getLogs();
