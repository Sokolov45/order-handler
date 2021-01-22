<?php

include "php/config.php";
include "php/db.class.php";
include "php/burger.class.php";

ini_set('display_errors', 'on');
ini_set("error_reporting", E_NOTICE|E_ALL);

$name = $_POST['name'];
$email = $_POST['email'];
$address = '';
$fields = ['street', 'home'];
foreach ($_POST as $item => $value) {
    if ($value && in_array($item, $fields)) {
        $address .= $value;
    }
}

$burger = new Burger();
$user = $burger->getUserByEmail($email);
die();
//if ($user) {
//    $ordersCount = $user['orders_count'] + 1;
//
//}else {
//    $ordersCount = 1;
//}
//
//$burger->createUser();
//
//$orderNumber = $burger->addOrder() ;
//
//echo "Ваш заказ принят,<br>
//Номер вашего заказа $orderNumber<br>
//Заказ будет доставлен по адрессу $address<br>
//Это ваш $ordersCount
//";