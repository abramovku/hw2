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
		$res->view('order/form.php');//render form
	}

	public function data(Request $req, Response $res)
	{
		$postData = $req->getJSON();
		$username = $postData['username'] ?? '';
		$password = $postData['password'] ?? '';


	}
}