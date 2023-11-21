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
		$postData = $req->getJSON();
		$user = $postData['user'] ?? '';
		$order = $postData['order'] ?? '';
		$price = $postData['price'] ?? '';

		if (
			empty($user)
			|| empty($order)
			|| empty($price)
		) {
			$errormessage = 'empty order data';
			$res->redirect($this->getRedirectUrl($req, '/order'));
		}

		$prepPrice = floatval($price);

		$orderId = $this->createOrder($user, $order, $prepPrice);

		if(!empty($orderId)){
			$res->toJSON([
				'message' => 'Order created successfully',
				'data' => compact(['user', 'order', 'prepPrice']),
			]);
		}
	}

	private function createOrder($user, $order, $price)
	{
		$PDO = Db::getInstance();
		$PDO->query("insert into orders (token, user_id, price) values ('$order', $user, $price);");
		return $PDO->last_insert_id();
	}
}