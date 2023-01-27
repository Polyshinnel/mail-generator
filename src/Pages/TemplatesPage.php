<?php


namespace App\Pages;


use App\Controllers\MailBlockGenerator;
use App\Controllers\MailCreator;
use App\Controllers\ScreenShooter;
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
    private $screenShot;
    private $mailCreator;
    private $mailBlockGenerator;

    public function __construct(Twig $twig,TemplatesController $templatesController,ScreenShooter $screenShot,MailCreator $mailCreator,MailBlockGenerator $mailBlockGenerator)
    {
        $this->twig = $twig;
        $this->templatesController = $templatesController;
        $this->screenShot = $screenShot;
        $this->mailCreator = $mailCreator;
        $this->mailBlockGenerator = $mailBlockGenerator;
    }

    public function get(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $templates = $this->templatesController->getAllRepository();

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

    public function getTemplate(ServerRequestInterface $request, ResponseInterface $response,array $args): ResponseInterface
    {
        $id = $args['id'];
        $template = $this->templatesController->getTemplateById($id);
        $name = $template['name'];
        $jsonArr = json_decode($template['json'],true);

        $settings = [];

        //get settings
        foreach ($jsonArr as $jsonItem) {
            if($jsonItem['blockName'] == 'settings') {
                $settings = $jsonItem;
            }
        }


        $mailBlocks = $this->mailCreator->finalCreateMail($jsonArr);
        $siteName = $this->mailCreator->getSiteName($jsonArr);

        $mail = $this->mailBlockGenerator->createMail($mailBlocks,$siteName,$settings);

        $data = $this->twig->fetch('template.twig', [
            'title' => $name,
            'json' => $jsonArr,
            'mail' => $mail,
            'id' => $id
        ]);


        return new Response(
            200,
            new Headers(['Content-Type' => 'text/html']),
            (new StreamFactory())->createStream($data)
        );
    }

    public function createTemplate(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {
        $dataArr = $request->getParsedBody();
        $json = $dataArr['json'];
        $name = $dataArr['name'];
        $this->templatesController->createTemplate($json,$name);

        return new Response(
            200,
            new Headers(['Content-Type' => 'text/html']),
            (new StreamFactory())->createStream('')
        );
    }

    public function updateTemplate(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {
        $dataArr = $request->getParsedBody();
        $json = $dataArr['json'];
        $id = $dataArr['id'];
        $name = $dataArr['name'];
        $this->templatesController->updateTemplate($id,$json,$name);

        return new Response(
            200,
            new Headers(['Content-Type' => 'text/html']),
            (new StreamFactory())->createStream('')
        );
    }

    public function deleteTemplate(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {
        $dataArr = $request->getParsedBody();
        $id = $dataArr['id'];
        $this->templatesController->deleteTemplate($id);

        return new Response(
            200,
            new Headers(['Content-Type' => 'text/html']),
            (new StreamFactory())->createStream('')
        );
    }
}