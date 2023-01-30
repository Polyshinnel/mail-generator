<?php


namespace App\Pages;


use App\Controllers\MailBlockGenerator;
use App\Controllers\MailCreator;
use App\Controllers\ScreenShooter;
use App\Controllers\TemplatesController;
use App\Controllers\Utils;
use App\Controllers\ZipController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Response;
use Slim\Psr7\Stream;
use Slim\Views\Twig;

class TemplatesPage
{
    private Twig $twig;
    private TemplatesController $templatesController;
    private ScreenShooter $screenShot;
    private MailCreator $mailCreator;
    private MailBlockGenerator $mailBlockGenerator;
    private ZipController $zipController;
    private Utils $utils;

    public function __construct(
        Twig $twig,
        TemplatesController $templatesController,
        ScreenShooter $screenShot,
        MailCreator $mailCreator,
        MailBlockGenerator $mailBlockGenerator,
        ZipController $zipController,
        Utils $utils
    )
    {
        $this->twig = $twig;
        $this->templatesController = $templatesController;
        $this->screenShot = $screenShot;
        $this->mailCreator = $mailCreator;
        $this->mailBlockGenerator = $mailBlockGenerator;
        $this->zipController = $zipController;
        $this->utils = $utils;
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
        $html = $template['html'];
        $html = htmlspecialchars_decode($html);
        $jsonArr = json_decode($template['json'],true);


        $data = $this->twig->fetch('template.twig', [
            'title' => $name,
            'json' => $jsonArr,
            'mail' => $html,
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
        $html = $dataArr['html'];
        $this->templatesController->createTemplate($json,$html,$name);

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
        $html = $dataArr['html'];
        $this->templatesController->updateTemplate($id,$json,$html,$name);

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

    public function getTemplateZip(ServerRequestInterface $request, ResponseInterface $response,array $args): ResponseInterface
    {
        $id = $args['id'];
        $template = $this->templatesController->getTemplateById($id);
        $html = $template['html'];
        $json = $template['json'];
        $json = json_decode($json, JSON_UNESCAPED_UNICODE);


        $html = htmlspecialchars_decode($html);
        $this->zipController->zipLetter($json,$html);
        $zip = __DIR__.'/../../public/assets/letter.zip';


        return new Response(
            200,
            new Headers([
                'Content-Type' => 'application/zip',
                'Content-Disposition' => 'attachment; filename="letter.zip"'
            ]),
            (new StreamFactory())->createStreamFromFile($zip)
        );
    }
}