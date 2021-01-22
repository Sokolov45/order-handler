<?php

class Burger
{

    public function getUserByEmail($email)
    {
        $db = Db::getInstance();
        $query = "SELECT * FROM users WHERE email = :email";
        $db->fetchOne($query, __METHOD__, [
            ":email" => $email
        ]);
    }
}