<?php

class Burger
{

    public function getUserByEmail(string $email)
    {
        $db = Db::getInstance();
        $query = "SELECT * FROM users WHERE email = :email";
        return $db->fetchOne($query, __METHOD__, [':email' => $email]);
    }

    public function createUser(string $name, string $email)
    {
        $db = Db::getInstance();
        $query = "INSERT INTO users(name, email) VALUES(:name, :email)";
        $result = $db->exec($query, __METHOD__, [":name" => $name, ':email' => $email]);
        if (!$result) {
            return false;
        }
        return $db->lastInsertId();
    }

    public function incOrders(int $id)
    {
        $db = Db::getInstance();
        $query = "UPDATE users SET orders_count = orders_count + 1 WHERE id = :userId";
        return $db->exec($query, __METHOD__, [':userId' => $id]);
    }

    public function addOrder(int $id, string $address)
    {
        $db = Db::getInstance();
        $query = "INSERT INTO orders(user_id, address, created_at) VALUES(:user_id, :address, :created_at)";
        $result =  $db->exec($query, __METHOD__, [
            ':user_id' => $id,
            ":address" => $address,
            ":created_at" => date("Y-m-d H:i:s")
        ] );
        if (!$result) {
            return false;
        }
        return $db->lastInsertId();
    }
}