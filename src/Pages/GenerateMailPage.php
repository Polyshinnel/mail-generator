<?php


namespace App\Pages;


use App\Controllers\MailBlockGenerator;
use App\Controllers\MailCreator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Response;

class GenerateMailPage
{
    private $mailCreator;
    private $mailBlockGenerator;

    public function __construct(MailCreator $mailCreator,MailBlockGenerator $mailBlockGenerator)
    {
        $this->mailCreator = $mailCreator;
        $this->mailBlockGenerator = $mailBlockGenerator;
    }

    public function get(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $dataArr = $request->getParsedBody();
        $jsonArr = json_decode($dataArr['json'],true);

        $mailBlocks = $this->mailCreator->finalCreateMail($jsonArr);
        $siteName = $this->mailCreator->getSiteName($jsonArr);

        $mail = $this->mailBlockGenerator->createMail($mailBlocks,$siteName);




        return new Response(
            200,
            new Headers(['Content-Type' => 'text/html']),
            (new StreamFactory())->createStream($mail)
        );
    }
}