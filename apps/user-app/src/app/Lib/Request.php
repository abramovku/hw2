<?php
namespace App\Lib;

class Request
{
    public $params;
    public $reqMethod;
    public $requestUri;
    public $contentType;

    public function __construct($params = [])
    {
        $this->params = $params;
        $this->reqMethod = trim($_SERVER['REQUEST_METHOD']);
        $this->requestUri =trim($_SERVER['REQUEST_URI']);
        $this->contentType = !empty($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
    }

    public function getBody()
    {
        if ($this->reqMethod !== 'POST' && $this->reqMethod !== 'PUT') {
            return '';
        }

        $body = [];
        foreach ($_POST as $key => $value) {
            $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
        }

        return $body;
    }

    public function getMethod()
    {
        return $this->reqMethod;
    }

    public function getRequestUri()
    {
        return $this->requestUri;
    }

    public function getJSON()
    {
        if ($this->reqMethod !== 'POST' && $this->reqMethod !== 'PUT') {
            return [];
        }

        if (strcasecmp($this->contentType, 'application/json') !== 0) {
            return [];
        }

        // Receive the RAW post data.
        $content = trim(file_get_contents("php://input"));
        $decoded = json_decode($content, true);

        return $decoded;
    }
}