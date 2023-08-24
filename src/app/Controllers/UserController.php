<?php

namespace App\Controllers;

use App\Lib\Request;
use App\Lib\Response;
use App\Lib\Db;
use App\Lib\Logger;

class UserController
{
    public function index(Request $req, Response $res)
    {
        $userId = $req->params[0];
        $PDO = Db::getInstance();
        $data = $PDO->fetchQuery("SELECT * FROM users WHERE id = $userId");

        $res->toJSON([
            "result" => $data
        ]);
    }

    public function create(Request $req, Response $res)
    {
        $postData = $req->getJSON();
        $username = $postData['username'] ?? '';
        $firstName = $postData['firstname'] ?? '';
        $lastName = $postData['lastname'] ?? '';
        $email = $postData['email'] ?? '';
        $phone = $postData['phone'] ?? '';
        $PDO = Db::getInstance();
        $data = $PDO->query("insert into users (username, firstname, lastname, email, phone) values ('$username', '$firstName', '$lastName', '$email', '$phone');");

        $res->toJSON([
            "result" => $data
        ]);
    }

    public function delete(Request $req, Response $res)
    {
        $userId = $req->params[0];
        $PDO = Db::getInstance();
        $data = $PDO->query("Delete from users where id = $userId;");

        $res->toJSON([
            "result" => $data
        ]);
    }

    public function update(Request $req, Response $res)
    {
        $userId = $req->params[0];

        $postData = $req->getJSON();
        $username = $postData['username'] ?? '';
        $firstName = $postData['firstname'] ?? '';
        $lastName = $postData['lastname'] ?? '';
        $email = $postData['email'] ?? '';
        $phone = $postData['phone'] ?? '';

        $PDO = Db::getInstance();
        $data = $PDO->query("UPDATE users SET username = '$username', firstname = '$firstName', lastname = '$lastName', email = '$email', phone = '$phone' WHERE id = $userId;");
       
        $res->toJSON([
            "result" => $data
        ]);
    }

    public function migrate(Request $req, Response $res)
    {
        $PDO = Db::getInstance();
        $PDO->query("CREATE TABLE IF NOT EXISTS users (
            id bigserial NOT NULL PRIMARY KEY,
            username varchar(48) NOT NULL,
            firstname varchar(48) NOT NULL,
            lastname varchar(48) NOT NULL,
            email text NOT NULL,
            phone text NOT NULL
        );");

        $res->toJSON([
            "result" => "success"
        ]);
    }
}