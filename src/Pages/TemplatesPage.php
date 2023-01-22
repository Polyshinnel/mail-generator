<?php


namespace App\Pages;


use App\Controllers\TemplatesController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Response;
use Slim\Views\Twig;

class TemplatesPage
{
    private $twig;
    private $templatesController;

    public function __construct(Twig $twig,TemplatesController $templatesController)
    {
        $this->twig = $twig;
        $this->templatesController = $templatesController;
    }

    public function get(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $templates = $this->templatesController->getAllRepository();

        $this->templatesController->updateImg('1');

        $data = $this->twig->fetch('templates.twig', [
            'title' => 'Шаблоны',
            'templates' => $templates
        ]);

        return new Response(
            200,
            new Headers(['Content-Type' => 'text/html']),
            (new StreamFactory())->createStream($data)
        );
    }
}