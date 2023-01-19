<?php


namespace App\Pages;

use App\Controllers\ImageProcessing;
use App\Controllers\SiteParser;
use App\Controllers\UploadImage;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Response;
use Slim\Views\Twig;

class IndexPage
{
    private $twig;
    private $parser;
    private $fileUploader;
    private $imageProcessing;

    public function __construct(Twig $twig,SiteParser $parser,UploadImage $fileUploader,ImageProcessing $imageProcessing)
    {
        $this->twig = $twig;
        $this->parser = $parser;
        $this->fileUploader = $fileUploader;
        $this->imageProcessing = $imageProcessing;
    }

    public function get(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = $this->twig->fetch('index.twig', [
            'title' => 'Главная страница',
        ]);

//        $productInfo = $this->parser->getMonbentoData('https://monbento.me/products/butilka-mb-positive-blush-330-ml?coupon=GIFT2023');
//        $fileName = $this->fileUploader->uploadFileFromLink($productInfo['productImg']);
//        $imgInfo = $this->imageProcessing->createWideImage($fileName);


        return new Response(
            200,
            new Headers(['Content-Type' => 'text/html']),
            (new StreamFactory())->createStream($data)
        );
    }
}