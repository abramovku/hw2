<?php

namespace App\Controllers;

use App\Lib\Request;
use App\Lib\Response;
use App\Lib\JWT;
use App\Lib\Db;
class LoginController extends Controller
{
    public function index(Request $req, Response $res)
    {
        $res->view('auth/form.php');//render form
    }

    public function logout(Request $req, Response $res)
    {
        if (!isset($_SESSION))
        {
            session_start();
        }
        session_destroy();
        $res->toJSON(['message' => 'logout successfully']);
    }

    public function data(Request $req, Response $res)
    {
        $postData = $req->getJSON();
        $username = $postData['username'] ?? '';
        $password = $postData['password'] ?? '';

        if (empty($username) || empty($password)) {
            $_POST['errormessage'] = 'empty login or password field';
            $res->redirect($this->getRedirectUrl($req));
        }

        //check user and pass exist
        if (!$this->checkCreds($username, $password)) {
            $_POST['errormessage'] = 'not valid credentials';
            $res->redirect($this->getRedirectUrl($req));
        }

        $token = (new JWT())->issue($username);

        session_start();
        $_SESSION['x-username'] = $username;
        $_SESSION['x-auth-token'] = $token;

        $res->toJSON([
            'message' => 'logined successfully',
            'token' => $token
        ]);
    }

    public function auth(Request $req, Response $res)
    {
        session_start();

        if (empty($_SESSION['x-username']) || empty($_SESSION['x-auth-token'])) {
            $res->redirect($this->getRedirectUrl($req));
        }

        $validator = (new JWT())->parse($_SESSION['x-auth-token']);
        if (!empty($validator['type']) && $validator['type'] === 'success') {
            header("x-auth-token: " . $_SESSION['x-auth-token']);
            header("x-username: " . $_SESSION['x-username']);
        }
    }

    private function getRedirectUrl(Request $req)
    {
        $host = !empty($req->getHeaderVal('X-Forwarded-Host')) ? $req->getHeaderVal('X-Forwarded-Host') :
            (!empty($req->getHeaderVal('x-forwarded-host')) ? $req->getHeaderVal('x-forwarded-host') : '');
        return 'http://' . $host . '/auth/login';
    }


    private function checkCreds(string $username, string $password): bool
    {
        $PDO = Db::getInstance();
        $data = $PDO->fetchQuery("SELECT 1 FROM credentials WHERE username = '$username' and password = '$password'");
        if (!empty($data)) {
            return true;
        }

        return false;
    }
}
