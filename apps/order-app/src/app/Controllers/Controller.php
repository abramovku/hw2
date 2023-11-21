<?php

namespace App\Controllers;

use App\Lib\Request;
use App\Lib\Response;

class Controller
{
    public $startTime;

    public function __construct()
    {
        $this->startTime = time();
    }

	protected function getRedirectUrl(Request $req, string $path)
	{
		$host = !empty($req->getHeaderVal('X-Forwarded-Host')) ? $req->getHeaderVal('X-Forwarded-Host') :
			(!empty($req->getHeaderVal('x-forwarded-host')) ? $req->getHeaderVal('x-forwarded-host') : '');
		return 'http://' . $host . $path;
	}
}