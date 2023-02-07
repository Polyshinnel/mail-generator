<?php


namespace App\Pages;


use phpgif\GIF\GIFGenerator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Response;

class GifPage
{
    private GIFGenerator $gifGen;

    public function __construct(GIFGenerator $gifGen)
    {
        $this->gifGen = $gifGen;
    }

    public function get(ServerRequestInterface $request, ResponseInterface $response,array $args): ResponseInterface
    {
        $imageFrames = [
            'repeat' => true,
            'frames' => [
                [
                    'image' => __DIR__.'/../../public/assets/images/gif/1.jpg',
                    'delay' => 100
                ],
                [
                    'image' => __DIR__.'/../../public/assets/images/gif/2.jpg',
                    'delay' => 100
                ],
                [
                    'image' => __DIR__.'/../../public/assets/images/gif/3.jpg',
                    'delay' => 100
                ],
                [
                    'image' => __DIR__.'/../../public/assets/images/gif/4.jpg',
                    'delay' => 100
                ],
                [
                    'image' => __DIR__.'/../../public/assets/images/gif/5.jpg',
                    'delay' => 100
                ],
                [
                    'image' => __DIR__.'/../../public/assets/images/gif/6.jpg',
                    'delay' => 100
                ],
            ]
        ];

        $gifBin = $this->gifGen->generate($imageFrames);
        file_put_contents(__DIR__.'/../../public/assets/images/gif/test.gif',$gifBin);
        $img = __DIR__.'/../../public/assets/images/gif/test.gif';

        return new Response(
            200,
            new Headers(['Content-Type' => 'image/gif']),
            (new StreamFactory())->createStreamFromFile($img)
        );
    }
}