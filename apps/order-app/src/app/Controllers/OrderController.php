<?php

namespace App\Controllers;

use App\Lib\Request;
use App\Lib\Response;
use App\Lib\Db;
use App\Lib\Logger;

class OrderController extends Controller
{
    public function index(Request $req, Response $res)
    {
        try{
            $userId = $req->params[0];

            if ($this->findById($userId) === false) {
                $res->status('404')
                    ->startTime($this->startTime)
                    ->request($req)
                    ->toJSON([
                    'message' => 'User not found'
                ]);
                return;
            }

            $PDO = Db::getInstance();
            $data = $PDO->fetchQuery("SELECT * FROM users WHERE id = $userId");

            $res->startTime($this->startTime)
                ->request($req)
                ->toJSON([
                "result" => $data
            ]);
        } catch (\Throwable $e) {
            $res->startTime($this->startTime)
                ->request($req)
                ->status('500')
                ->toJSON([
                "message" => $e->getMessage()
            ]);
        }
    }

    public function create(Request $req, Response $res)
    {
        try{
            $postData = $req->getJSON();
            $username = $postData['username'] ?? '';
            $firstName = $postData['firstname'] ?? '';
            $lastName = $postData['lastname'] ?? '';
            $email = $postData['email'] ?? '';
            $phone = $postData['phone'] ?? '';
            $PDO = Db::getInstance();
            $data = $PDO->query("insert into users (username, firstname, lastname, email, phone) values ('$username', '$firstName', '$lastName', '$email', '$phone');");

            $res->startTime($this->startTime)
                ->request($req)
                ->toJSON([
                "result" => $data
            ]);
        } catch (\Throwable $e) {
            $res->startTime($this->startTime)
                ->request($req)
                ->status('500')
                ->toJSON([
                "message" => $e->getMessage()
            ]);
        }
    }

    /**
     * @param $id
     * @return bool
     */
    private function findById($id): bool
    {
        $PDO = Db::getInstance();
        $data = $PDO->fetchQuery("SELECT 1 FROM users WHERE id = $id");
        if (!empty($data)) {
            return true;
        }

        return false;
    }
}