<?php
namespace App\Lib;

use App\Lib\Logger;
class App
{
    public static function run()
    {
        Logger::enableSystemLogs();
    }
}