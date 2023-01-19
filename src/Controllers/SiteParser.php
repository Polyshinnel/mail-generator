<?php


namespace App\Controllers;

use simplehtmldom\HtmlWeb;

class SiteParser
{
    private $client;

    public function __construct(HtmlWeb $client)
    {
        $this->client = $client;
    }

    public function getMonbentoData(String $link){
        $html = $this->client->load($link);
        $imgObj = $html->find('.product_img-box a');
        $imgObj = $imgObj[0];
        $imgSrc = $imgObj->href;

        $productNameObj = $html->find('.product_info h1');
        $productNameObj = $productNameObj[0];
        $productName = $productNameObj->innertext;

        return [
            'productName' => $productName,
            'productImg' => $imgSrc
        ];
    }
}