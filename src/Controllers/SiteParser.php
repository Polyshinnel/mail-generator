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
        $user = $settings[0]['feed_user'];
        $pass = $settings[0]['feed_pass'];


        $ch = curl_init($xmlUrl);
        curl_setopt($ch, CURLOPT_USERPWD, "$user:$pass");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $xmlRaw = curl_exec($ch);
        curl_close($ch);

        $xml = simplexml_load_string($xmlRaw);

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

        $_SESSION[$hostName] = $productsArr;

        return $productsArr[$link];
    }
}