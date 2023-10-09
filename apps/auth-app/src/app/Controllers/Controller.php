<?php

namespace App\Controllers;

class Controller
{
    public $startTime;

    public function __construct()
    {
        $this->startTime = time();
    }
}