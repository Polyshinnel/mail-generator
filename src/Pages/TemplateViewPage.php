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
        $template = $this->templatesController->getTemplateViewById($id);
        $html = $template['html'];
        $html = htmlspecialchars_decode($html);

        return new Response(
            200,
            new Headers(['Content-Type' => 'text/html']),
            (new StreamFactory())->createStream($html)
        );
    }
}