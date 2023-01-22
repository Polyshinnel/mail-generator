<?php


namespace App\Pages;


use App\Controllers\MailBlockGenerator;
use App\Controllers\MailCreator;
use App\Controllers\TemplatesController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Response;

class TemplateViewPage
{
    private $mailCreator;
    private $mailBlockGenerator;
    private $templatesController;

    public function __construct(MailCreator $mailCreator,MailBlockGenerator $mailBlockGenerator,TemplatesController $templatesController)
    {
        $this->mailCreator = $mailCreator;
        $this->mailBlockGenerator = $mailBlockGenerator;
        $this->templatesController = $templatesController;
    }

    public function get(ServerRequestInterface $request, ResponseInterface $response,array $args): ResponseInterface
    {
        $id = $args['id'];
        $json = $this->templatesController->getTemplateViewById($id);
        $jsonArr = json_decode($json,true);
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