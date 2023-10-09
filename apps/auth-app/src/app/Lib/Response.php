<?php
namespace App\Lib;

class Response
{
    private $status = 200;
    private $startTime;
    private $request;

    public function __construct()
    {
        //
    }

    public function status(int $code)
    {
        $this->status = $code;
        return $this;
    }

    public function startTime(int $time)
    {
        $this->startTime = $time;
        return $this;
    }

    public function request(Request $request)
    {
        $this->request = $request;
        return $this;
    }

    public function toJSON($data = [])
    {
        http_response_code($this->status);
        header('Content-Type: application/json');
        if (!empty($this->redirect)) {
            header('Location: ' . $this->redirect);
        }
        echo json_encode($data);
    }

    public function raw(string $data)
    {
        http_response_code($this->status);
        echo $data;
    }

    public function view(string $path)
    {
        http_response_code($this->status);
        (new View())->render($path);
    }

    public function redirect(string $url)
    {
        header('Location: ' . $url, true, 303);
        exit();
    }
}