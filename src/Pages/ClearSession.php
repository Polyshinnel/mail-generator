<?php


namespace App\Pages;


use App\Controllers\UploadImage;
use App\Controllers\Utils;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Response;
use Slim\Views\Twig;

class ClearSession
{

    public function get(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $_SESSION = array();
        $json = [
            'msg' => 'Сессия сброшена'
        ];
        $json = json_encode($json,JSON_UNESCAPED_UNICODE);

        return new Response(
            200,
            new Headers(['Content-Type' => 'text/html']),
            (new StreamFactory())->createStream($json)
        );
    }
}