<?php


namespace App\Pages;


use App\Controllers\UploadImage;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Response;
use Slim\Views\Twig;

class UploadPage
{
    private $uploadClass;

    public function __construct(UploadImage $uploadClass)
    {
        $this->uploadClass = $uploadClass;
    }

    public function get(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $fileName = $this->uploadClass->uploadFile();
        $fileLink = 'https://'.$_SERVER['HTTP_HOST'].'/assets/uploaded/'.$fileName;
        $json = [
            'fileLink' => $fileLink
        ];

        $json = json_encode($json,JSON_UNESCAPED_UNICODE);

        return new Response(
            200,
            new Headers(['Content-Type' => 'text/html']),
            (new StreamFactory())->createStream($json)
        );
    }
}