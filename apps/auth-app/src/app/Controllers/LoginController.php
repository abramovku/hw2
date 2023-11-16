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
	    $register_url = $this->getRedirectUrl($req,'/register');
        $res->view('auth/form.php', compact('register_url'));//render form
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
	        $errormessage = 'empty login or password field';
            $res->redirect($this->getRedirectUrl($req, '/login'));
        }

        //check user and pass exist
        if (!$this->checkCreds($username, $password)) {
	        $errormessage = 'not valid credentials';
            $res->redirect($this->getRedirectUrl($req, '/login'));
        }

	    $userId = $this->getUserId($username);

        $token = (new JWT())->issue($username);

        session_start();
        $_SESSION['x-username'] = $username;
	    $_SESSION['x-userid'] = $userId;
        $_SESSION['x-auth-token'] = $token;

        $res->toJSON([
            'message' => 'logined successfully',
            'token' => $token
        ]);
    }

    public function auth(Request $req, Response $res)
    {
        session_start();

        if (empty($_SESSION['x-username']) || empty($_SESSION['x-auth-token'])
	        || empty($_SESSION['x-userid'])) {
            $res->redirect($this->getRedirectUrl($req, '/login'));
        }

        $validator = (new JWT())->parse($_SESSION['x-auth-token']);
        if (!empty($validator['type']) && $validator['type'] === 'success') {
            header("x-auth-token: " . $_SESSION['x-auth-token']);
            header("x-username: " . $_SESSION['x-username']);
	        header("x-userid: " . $_SESSION['x-userid']);
        }
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

	private function getUserId(string $username): int
	{
		$PDO = Db::getInstance();
		$data = $PDO->fetchQuery("SELECT id FROM credentials WHERE username = '$username'");
		return $data['user_id'] ?? 0;
	}
}
