<?php


namespace App\Controllers;

use App\Repository\SiteSettingsRepository;

class SiteParser
{
    private SiteSettingsRepository $siteSettings;
    private Utils $utils;

    public function __construct(SiteSettingsRepository $siteSettings, Utils $utils)
    {
        $this->siteSettings = $siteSettings;
        $this->utils = $utils;
    }

    public function getProductData(String $link): array {
        $urlArr = parse_url($link);
        $link = $urlArr['scheme'].'://'.$urlArr['host'].$urlArr['path'];
        $hostName = $urlArr['host'];
        $settings = $this->siteSettings->findSettingsByHost($hostName);

        $xmlUrl = $settings[0]['site_xml'];
        $xml = simplexml_load_file($xmlUrl);

        $offers = $xml->shop->offers->offer;

        $productsArr = [];
        foreach ($offers as $offer) {
            $productUrl = (String)$offer->url;

            $productPrefix = (String)$offer->typePrefix;
            $productModel = (String)$offer->model;
            $productModel = explode('(',$productModel);
            $productModel = $productModel[0];
            $productModel = trim($productModel);
            $productModelArr = explode(' ',$productModel);
            $productModel = '';
            foreach ($productModelArr as $productModelItem) {
                $productModel .= ucfirst($productModelItem).' ';
            }
            $productModel = trim($productModel);
            $productName = $productPrefix.' '.$productModel;
            $productName = trim($productName);
            $productName = $this->utils->mbUcfirst($productName);

            $productPictures = $offer->picture;
            $productPicture = (String)$productPictures[0];
            $price = (String)$offer->price;
            $oldPrice = '';
            if(isset($offer->oldprice)) {
                $oldPrice = (String)$offer->oldprice;
            }

            $productsArr[$productUrl] = [
                'productName' => $productName,
                'productImg' => $productPicture,
                'price' => $price,
                'oldPrice' => $oldPrice
            ];
        }

        return $productsArr[$link];
    }
}