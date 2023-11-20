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
			'data' => [$username, $userId, $token],
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
	        $headers =[
		        "X-Auth-Token" => $_SESSION['x-auth-token'],
		        "X-Username" => $_SESSION['x-username'],
		        "X-Userid" => $_SESSION['x-userid']
	        ];

			$res->setHeader($headers);
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
		return $data[0]['id'] ?? 0;
	}

	private function findById(int $id): bool
	{
		$PDO = Db::getInstance();
		$data = $PDO->fetchQuery("SELECT 1 FROM credentials WHERE id = $id");
		if (!empty($data)) {
			return true;
		}

		return false;
	}

	public function user(Request $req, Response $res)
	{
		try{
			$userId = $req->params[0];

			if ($this->findById($userId) === false) {
				$res->toJSON([
					'message' => 'User not found'
				]);
				return;
			}

			$PDO = Db::getInstance();
			$data = $PDO->fetchQuery("SELECT * FROM credentials WHERE id = $userId");

			$res->toJSON([
				"result" => $data
			]);
		} catch (\Throwable $e) {
			$res->status('500')
				->toJSON([
					"message" => $e->getMessage()
				]);
		}
	}
}
