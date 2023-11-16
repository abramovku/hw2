<?php

namespace app\Controllers;

use App\Lib\Request;
use App\Lib\Response;
use App\Lib\JWT;
use App\Lib\Db;
class RegisterController extends Controller
{
    public function index(Request $req, Response $res)
    {
		$login_url = $this->getRedirectUrl($req,'/login');
        $res->view('auth/register_form.php', compact('login_url'));//render form
    }

    public function data(Request $req, Response $res)
    {
        $postData = $req->getJSON();
        $username = $postData['username'] ?? '';
        $password = $postData['password'] ?? '';
	    $fname = $postData['fname'] ?? '';
	    $lname = $postData['lname'] ?? '';
	    $email = $postData['email'] ?? '';


        if (
			empty($username)
			|| empty($password)
			|| empty($fname)
	        || empty($lname)
			|| empty($email)
        ) {
            $errormessage = 'empty register data';
            $res->redirect($this->getRedirectUrl($req, '/register'));
        }


        $userId = $this->createUser($username, $password, $fname, $lname, $email);

		if(!empty($userId)){
			$token = (new JWT())->issue($username);

			session_start();
			$_SESSION['x-username'] = $username;
			$_SESSION['x-userid'] = $userId;
			$_SESSION['x-auth-token'] = $token;

			$res->toJSON([
				'message' => 'User created successfully',
				'token' => $token
			]);
		}
    }

	private function createUser($username, $password, $fname, $lname, $email)
	{
		$PDO = Db::getInstance();
		$PDO->query("insert into credentials (username, firstname, lastname, email, password) values
             ('$username', '$fname', '$lname', '$email', '$password');");
		return $PDO->last_insert_id();
	}
}
