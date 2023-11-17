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
		$userId = $req->getHeaderVal('X-Userid');
		$login = $req->getHeaderVal('X-Username');

		$res->view('order/form.php', compact('userId','login'));//render form
	}

	public function data(Request $req, Response $res)
	{
		//
	}
}