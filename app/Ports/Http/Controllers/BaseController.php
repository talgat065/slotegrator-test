<?php declare(strict_types=1);

namespace App\Ports\Http\Controllers;

use Http\HttpResponse;

class BaseController
{
    protected HttpResponse $response;

    public function __construct()
    {
        $this->response = new HttpResponse();
    }

    public function success(string $msg, ?array $data)
    {
        $this->response->setStatusCode(200);
        $this->response->setHeader('Content-Type', 'application/json');
        $data = [
            'status' => 'success',
            'message' => $msg,
            'data' => $data,
        ];

        $this->response->setContent(json_encode($data));

        return $this->response->getContent();
    }

    public function error(array $data)
    {
        $this->response->setStatusCode(400);
        $this->response->setHeader('Content-Type', 'application/json');
        $this->response->setContent(json_encode($data));

        return $this->response->getContent();
    }
}
