<?php


namespace App\Controllers;


use App\Repository\SiteSettingsRepository;

class MailBlockGenerator
{
    private SiteParser $parser;
    private UploadImage $fileUploader;
    private ImageProcessing $imageProcessing;
    private SiteSettingsRepository $settingsRepository;
    private Utils $utils;

    public function __construct(
        SiteParser $parser,
        UploadImage $fileUploader,
        ImageProcessing $imageProcessing,
        SiteSettingsRepository $settingsRepository,
        Utils $utils
    )
    {
        $this->parser = $parser;
        $this->fileUploader = $fileUploader;
        $this->imageProcessing = $imageProcessing;
        $this->settingsRepository = $settingsRepository;
        $this->utils = $utils;
    }


    public function createMail(String $mailBlocks,String $siteName,array $settings): String {
        $siteInfo = $this->settingsRepository->findSettingsByName($siteName);
        $siteInfo = $siteInfo[0];
        $mailTheme = $settings['mailTheme'];
        $saleText = '-'.$settings['salePercent'].'%';
        $coupon = $settings['couponName'];
        $saleMotto = 'Скидка '.$settings['salePercent'].'% только по промокоду!';


        $mailHead = '
            <html lang="ru">
            
            <head>
              <meta charset="utf-8">
            
              <title>'.$siteInfo['name'].'</title>
            </head>
            
            <body
              style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; font-family: Verdana, sans-serif; font-size: 12px; font-weight: normal; line-height: 1; margin: 0; min-width: 100%; padding: 0; width: 100% !important;">
              <div itemscope="" itemtype="http://schema.org/DiscountOffer">
                <meta itemprop="description" content="'.$saleText.'">
                <meta itemprop="discountCode" content="'.$coupon.'">
              </div>
              <div itemscope="" itemtype="http://schema.org/EmailMessage">
                <meta itemprop="subjectLine" content="'.$mailTheme.'">
              </div>
              <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" style="padding: 15px">
                <tr>
                  <td align="center" valign="top">
                    <table border="0" cellspacing="0" cellpadding="0" width="540"
                      style=" padding:0 30px; border: 1px solid #ECECEC; border-radius: 10px;">
                      <tr>
                        <td>
                          <div style="display: none; max-height: 0px; overflow: hidden;">'.$saleMotto.'</div>
                          <div style="display: none; max-height: 0px; overflow: hidden;">
                            &nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;
                          </div>
                        </td>
                      </tr>';

        $mailBottom = '</table>
                      </td>
                    </tr>
                  </table>
                
                </body>
                
                </html>';

        return $mailHead.$mailBlocks.$mailBottom;
    }

    public function createHeader(String $siteName): String {
        $baseUrl = $this->utils->getCurrUrl();
        $imgAdd = $baseUrl.'/assets/';

        $siteInfo = $this->settingsRepository->findSettingsByName($siteName);
        $siteInfo = $siteInfo[0];

        $logoPath = __DIR__.'/../../public/assets/'.$siteInfo['logo'];
        $logoSize = $this->imageProcessing->getImageSizeByPath($logoPath);

        return '<tr>
            <td>
              <table border="0" cellspacing="0" cellpadding="0" width="100%" class="header"
                style="color: #B2B2B2; font-size: 11px; letter-spacing: -0.7px; padding: 4px 0 14px;">
                <tr valign="top">
                  <td align="left" width="150">
                    У вас отличный вкус!
                  </td>
                  <td align="center" class="logo" style="padding-top: 8px; padding-bottom: 8px;">
                    <a href="'.$siteInfo['site_addr'].'">
                      <img src="'.$imgAdd.$siteInfo['logo'].'" alt="'.$siteName.'" width="'.$logoSize['width'].'" height="'.$logoSize['height'].'">
                    </a>
                  </td>
                  <td align="right" width="150">
                    <a href="{{WebLetterUrl}}" style="color: #B2B2B2; text-decoration: none;">
                      Web-версия
                    </a>
                  </td>
                </tr>
              </table>
            </td>
          </tr>';
    }

    public function createFooter(String $siteName): String {
        $baseUrl = $this->utils->getCurrUrl();
        $imgAddr = $baseUrl.'/assets/';

        $siteInfo = $this->settingsRepository->findSettingsByName($siteName);
        $siteInfo = $siteInfo[0];

        return '<tr align="center" valign="top" class="copyright"
            style="font-size: 12px; line-height: 25px; text-align: center;">
            <td style="padding: 75px 0 25px;">
              <a href="'.$siteInfo['site_addr'].'"
                style="color: #2E78E8; text-decoration: none;">'.$siteInfo['site_addr_short'].'</a> © 2022. Есть вопросы? Звоните — 8 800
              100-49-32
            </td>
          </tr>
          <tr>
            <td>
              <table border="0" cellpadding="0" cellspacing="0" width="100%" class="footer__table"
                style="border-top: 1px solid #ECECEC; padding-top: 6px;">
                <tr valign="top">
                  <td>
                    <table border="0" cellpadding="0" cellspacing="0" class="footer__table__item" width="172"
                      style="background-color: #ffffff; border-radius: 5px; text-align: center;">
                      <tr>
                        <td class="footer__table__image" style="height: 95px; padding: 20px 0 12px;">
                          <a href="'.$siteInfo['delivery'].'"
                            style="color: #000000; text-decoration: none;">
                            <img src="'.$imgAddr.'images/mailadds/icon_1.jpg" alt="Доставка" width="64" height="64">
                          </a>
                        </td>
                      </tr>
                      <tr valign="top">
                        <td class="footer__table__text"
                          style="color: #000000; font-size: 12px; height: 84px; line-height: 20px;">
                          <a href="'.$siteInfo['delivery'].'"
                            style="color: #000000; text-decoration: none;">
                            Бесплатная доставка<br>
                            по России при заказе<br>
                            всего от 2500 руб.
                          </a>
                        </td>
                      </tr>
                    </table>

                  </td>
                  <td align="center">
                    <table border="0" cellpadding="0" cellspacing="0" class="footer__table__item" width="172"
                      style="background-color: #ffffff; border-radius: 5px; text-align: center;">
                      <tr>
                        <td class="footer__table__image" style="height: 95px; padding: 20px 0 12px;">
                          <a href="'.$siteInfo['site_addr'].'" style="color: #000000; text-decoration: none;">
                            <img src="'.$imgAddr.'images/mailadds/icon_2.jpg" alt="Магазин" width="55" height="55">
                          </a>
                        </td>
                      </tr>
                      <tr valign="top">
                        <td class="footer__table__text"
                          style="color: #000000; font-size: 12px; height: 84px; line-height: 20px;">
                          <a href="'.$siteInfo['site_addr'].'" style="color: #000000; text-decoration: none;">
                            Фирменный<br>
                            магазин '.$siteName.'<br>
                            В России
                          </a>
                        </td>
                      </tr>
                    </table>
                  </td>
                  <td align="right">

                    <table border="0" cellpadding="0" cellspacing="0" class="footer__table__item" width="172"
                      style="background-color: #ffffff; border-radius: 5px; text-align: center;">
                      <tr>
                        <td class="footer__table__image" style="height: 95px; padding: 20px 0 12px;">
                          <a href="'.$siteInfo['discount'].'" style="color: #000000; text-decoration: none;">
                            <img src="'.$imgAddr.'images/mailadds/icon_3.jpg" alt="Скидки" width="54" height="54">
                          </a>
                        </td>
                      </tr>
                      <tr valign="top">
                        <td class="footer__table__text"
                          style="color: #000000; font-size: 12px; height: 84px; line-height: 20px;">
                          <a href="'.$siteInfo['discount'].'" style="color: #000000; text-decoration: none;">
                            Накопительная скидка<br>
                            для постоянных<br>
                            покупателей
                          </a>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
            </td>
          </tr>';
    }

    public function createBanner(String $imgLink,String $bannerLink,String $name,array $settings): String {
        $urlArr = parse_url($imgLink);
        $path = __DIR__.'/../../public/'.$urlArr['path'];
        $bannerSizeOrigin = $this->imageProcessing->getImageSizeByPath($path);
        $bannerSize = $bannerSizeOrigin;
        if($bannerSizeOrigin['width'] != 540){
            $this->imageProcessing->resampleBanner($imgLink);
            $bannerSize = $this->imageProcessing->getImageSizeByPath($path);
        }



        if(!empty($settings['couponName'])) {
            $bannerLink = $bannerLink.'?coupon='.$settings['couponName'];
        }

        return '<tr align="center" valign="top">
            <td style="padding-bottom:0;">
              <a href="'.$bannerLink.'" style="text-decoration: none;">
                <img src="'.$imgLink.'" alt="'.$name.'"
                  width="'.$bannerSize['width'].'" height="'.$bannerSize['height'].'">
              </a>
            </td>
          </tr>';
    }

    public function createTimer(): String {
        $baseUrl = $this->utils->getCurrUrl();
        $imgAddr = $baseUrl.'/assets/';
        return '<tr>
            <td align="center">
              <img src="'.$imgAddr.'images/mailadds/timer.jpg" width="540" height="87" />
            </td>
          </tr>';
    }

    public function createProductsBlock(array $productsBlock,array $settings): String {
        $nameBlock =  $productsBlock['blockName'];
        $blockData = '';

        if($nameBlock == 'single-product') {
            $products = $productsBlock['products'];
            $productsData = $this->productProcessing($products,$nameBlock,$settings);
            $blockData = '<table width="100%" border="0" cellpadding="0" cellspacing="0" style="padding: 13px 0;">
                <tr valign="middle">
                  <td align="center" style="background-color: #F5F5F5">
                    <a href="'.$productsData[0]['link'].'"
                      style="text-decoration: none;">
                      <img src="'.$productsData[0]['img'].'" alt="'.$productsData[0]['name'].'" width="540"
                        height="370">
                      <div style="text-align: left; padding: 0 24px 24px">
                        <div style="font-size: 16px;line-height: 23px;">
                          <span style="color: #39B54A;">'.$productsData[0]['price'].' руб.</span>
                          <s style="color: #000;">'.$productsData[0]['oldPrice'].' руб.</s>
                        </div>
                        <span class="product__title"
                          style="color: #000000; font-size: 16px; line-height: 23px; padding-right: 10px;">
                          '.$productsData[0]['name'].'
                        </span>
                      </div>
                    </a>
                  </td>
                </tr>
              </table>';
        }

        if($nameBlock == 'two-product') {
            $products = $productsBlock['products'];
            $productsData = $this->productProcessing($products,$nameBlock,$settings);

            $blockData = '<table width="100%" border="0" cellpadding="0" cellspacing="0" style="padding: 13px 0;">
                <tr valign="top">
                  <td align="left">
                    <div style="text-align: left; width: 256px;">
                      <a href="'.$productsData[0]['link'].'"
                        class="product" style="text-decoration: none;">
                        <img src="'.$productsData[0]['img'].'" alt="'.$productsData[0]['name'].'" width="256"
                          height="255" style="margin-bottom: 13px;">
                        <div style="font-size: 12px;line-height: 23px;">
                          <span style="color: #39B54A;">'.$productsData[0]['price'].' руб.</span>
                          <s style="color: #000;">'.$productsData[0]['oldPrice'].' руб.</s>
                        </div>
                        <span class="product__title"
                          style="color: #000000; font-size: 12px; line-height: 23px; padding-right: 10px;">
                          '.$productsData[0]['name'].'
                        </span>
                      </a>
                    </div>
                  </td>

                  <td align="right">
                    <div style="text-align: left; width: 256px;">
                      <a href="'.$productsData[1]['link'].'"
                        class="product" style="text-decoration: none;">
                        <img src="'.$productsData[1]['img'].'" alt="'.$productsData[1]['name'].'" width="256"
                          height="255" style="margin-bottom: 13px;">
                        <div style="font-size: 12px;line-height: 23px;">
                          <span style="color: #39B54A;">'.$productsData[1]['price'].' руб.</span>
                          <s style="color: #000;">'.$productsData[1]['oldPrice'].' руб.</s>
                        </div>
                        <span class="product__title"
                          style="color: #000000; font-size: 12px; line-height: 23px; padding-right: 10px;">
                          '.$productsData[1]['name'].'
                        </span>
                      </a>
                    </div>
                  </td>
                </tr>
              </table>';
        }

        if($nameBlock == 'two-product-1') {
            $products = $productsBlock['products'];
            $productsData = $this->productProcessing($products,$nameBlock,$settings);

            $blockData = '<table width="100%" border="0" cellpadding="0" cellspacing="0" style="padding: 13px 0;">
                <tr valign="middle">
                  <td align="left">
                    <div style="text-align: left; width: 351px;">
                      <a href="'.$productsData[0]['link'].'"
                        class="product" style="text-decoration: none;">
                        <img src="'.$productsData[0]['img'].'" alt="'.$productsData[0]['name'].'" width="351"
                          height="350" style="margin-bottom: 13px;">
                        <div style="font-size: 12px;line-height: 23px;">
                          <span style="color: #39B54A;">'.$productsData[0]['price'].' руб.</span>
                          <s style="color: #000;">'.$productsData[0]['oldPrice'].' руб.</s>
                        </div>
                        <span class="product__title"
                          style="color: #000000; font-size: 12px; line-height: 23px; padding-right: 10px;">
                          '.$productsData[0]['name'].'
                        </span>
                      </a>
                    </div>
                  </td>
                  <td align="right">
                    <div style="text-align: left; width: 162px;">
                      <a href="'.$productsData[1]['link'].'"
                        class="product" style="text-decoration: none;">
                        <img src="'.$productsData[1]['img'].'" alt="'.$productsData[1]['name'].'" width="162"
                          height="162" style="margin-bottom: 13px;">
                        <div style="font-size: 12px;line-height: 23px;">
                          <span style="color: #39B54A;">'.$productsData[1]['price'].' руб.</span>
                          <s style="color: #000;">'.$productsData[1]['oldPrice'].' руб.</s>
                        </div>
                        <span class="product__title"
                          style="color: #000000; font-size: 12px; line-height: 23px; padding-right: 10px;">
                          '.$productsData[1]['name'].'
                        </span>
                      </a>
                    </div>
                  </td>
                </tr>
              </table>';
        }

        if($nameBlock == 'two-product-2') {
            $products = $productsBlock['products'];
            $productsData = $this->productProcessing($products,$nameBlock,$settings);

            $blockData = '<table width="100%" border="0" cellpadding="0" cellspacing="0" style="padding: 13px 0;">
                <tr valign="middle">
                  <td align="left">
                    <div style="text-align: left; width: 162px;">
                      <a href="'.$productsData[0]['link'].'"
                        class="product" style="text-decoration: none;">
                        <img src="'.$productsData[0]['img'].'" alt="'.$productsData[0]['name'].'" width="162"
                          height="162" style="margin-bottom: 13px;">
                        <div style="font-size: 12px;line-height: 23px;">
                          <span style="color: #39B54A;">'.$productsData[0]['price'].' руб.</span>
                          <s style="color: #000;">'.$productsData[0]['oldPrice'].' руб.</s>
                        </div>
                        <span class="product__title"
                          style="color: #000000; font-size: 12px; line-height: 23px; padding-right: 10px;">
                          '.$productsData[0]['name'].'
                        </span>
                      </a>
                    </div>
                  </td>
                  <td align="right">
                    <div style="text-align: left; width: 351px;">
                      <a href="'.$productsData[1]['link'].'"
                        class="product" style="text-decoration: none;">
                        <img src="'.$productsData[1]['img'].'" alt="'.$productsData[1]['name'].'" width="351"
                          height="350" style="margin-bottom: 13px;">
                        <div style="font-size: 12px;line-height: 23px;">
                          <span style="color: #39B54A;">'.$productsData[1]['price'].' руб.</span>
                          <s style="color: #000;">'.$productsData[1]['oldPrice'].' руб.</s>
                        </div>
                        <span class="product__title"
                          style="color: #000000; font-size: 12px; line-height: 23px; padding-right: 10px;">
                          '.$productsData[1]['name'].'
                        </span>
                      </a>
                    </div>
                  </td>
                </tr>
              </table>';
        }

        if($nameBlock == 'three-product') {
            $products = $productsBlock['products'];
            $productsData = $this->productProcessing($products,$nameBlock,$settings);

            $blockData = '<table width="100%" border="0" cellpadding="0" cellspacing="0" style="padding: 13px 0;">
                <tr valign="top">
                  <td align="left">
                    <div style="text-align: left; width: 162px;">
                      <a href="'.$productsData[0]['link'].'"
                        class="product" style="text-decoration: none;">
                        <img src="'.$productsData[0]['img'].'" alt="'.$productsData[0]['name'].'" width="162"
                          height="162" style="margin-bottom: 13px;">
                        <div style="font-size: 12px;line-height: 23px;">
                          <span style="color: #39B54A;">'.$productsData[0]['price'].' руб.</span>
                          <s style="color: #000;">'.$productsData[0]['oldPrice'].' руб.</s>
                        </div>
                        <span class="product__title"
                          style="color: #000000; font-size: 12px; line-height: 23px; padding-right: 10px;">
                          '.$productsData[0]['name'].'
                        </span>
                      </a>
                    </div>
                  </td>
                  <td align="center">
                    <div style="text-align: left; width: 162px;">
                      <a href="'.$productsData[1]['link'].'"
                        class="product" style="text-decoration: none;">
                        <img src="'.$productsData[1]['img'].'" alt="'.$productsData[1]['name'].'" width="162"
                          height="162" style="margin-bottom: 13px;">
                        <div style="font-size: 12px;line-height: 23px;">
                          <span style="color: #39B54A;">'.$productsData[1]['price'].' руб.</span>
                          <s style="color: #000;">'.$productsData[1]['oldPrice'].' руб.</s>
                        </div>
                        <span class="product__title"
                          style="color: #000000; font-size: 12px; line-height: 23px; padding-right: 10px;">
                          '.$productsData[1]['name'].'
                        </span>
                      </a>
                    </div>
                  </td>
                  <td align="right">
                    <div style="text-align: left; width: 162px;">
                      <a href="'.$productsData[2]['link'].'"
                        class="product" style="text-decoration: none;">
                        <img src="'.$productsData[2]['img'].'" alt="'.$productsData[2]['name'].'" width="162"
                          height="162" style="margin-bottom: 13px;">
                        <div style="font-size: 12px;line-height: 23px;">
                          <span style="color: #39B54A;">'.$productsData[2]['price'].' руб.</span>
                          <s style="color: #000;">'.$productsData[2]['oldPrice'].' руб.</s>
                        </div>
                        <span class="product__title"
                          style="color: #000000; font-size: 12px; line-height: 23px; padding-right: 10px;">
                          '.$productsData[2]['name'].'
                        </span>
                      </a>
                    </div>
                  </td>
                </tr>
              </table>';
        }

        return '<tr align="center" valign="top"><td>'.$blockData.'</td></tr>';
    }

    private function uploadDataToCache(array $productItem): array {
        $link = $productItem['link'];
        $urlArr = parse_url($link);

        //Get HostName
        $hostName = $urlArr['host'];

        //Get link without get request
        $link = $urlArr['scheme'].'://'.$urlArr['host'].$urlArr['path'];

        if(!empty($_SESSION[$link])) {
            return $_SESSION[$link];
        }

        if(!empty($_SESSION[$hostName])) {
            $productInfo = $_SESSION[$hostName][$link];
        } else {
            $productInfo = $this->parser->getProductData($link);
        }


        $fileName = $this->fileUploader->uploadFileFromLink($productInfo['productImg']);

        $_SESSION[$link] = [
            'link' => $link,
            'name' => $productInfo['productName'],
            'filename' => $fileName,
            'price' => $productInfo['price'],
            'oldPrice' => $productInfo['oldPrice']
        ];


        return [
            'link' => $link,
            'name' => $productInfo['productName'],
            'filename' => $fileName,
            'price' => $productInfo['price'],
            'oldPrice' => $productInfo['oldPrice']
        ];
    }

    private function addSaleInfo(array $sales,String $filename): String {
        $productImg = '';
        for($i = 0; $i < count($sales);$i++){
            $offset = $i*62;
            $color = $sales[$i]['color'];
            $text = '-'.$sales[$i]['text'].'%';
            $productImg = $this->imageProcessing->addSaleBlock($offset,$filename,$text,$color);
        }
        return $productImg;
    }

    private function addSales(array $settings,array $productData,array $sales): array {
        $salesArr = [];
        $price = (int)$productData['price'];
        $oldPrice = (int)$productData['oldPrice'];

        if(!$settings['ignoreSite']) {
            if((!empty($oldPrice)) && ($oldPrice > $price)) {
                $percent = ceil(($price/$oldPrice)*100);
                $percent = 100 - $percent;
                $color = $settings['saleColor1'];
                $salesArr[] = [
                    'text' => $percent,
                    'color' => $color
                ];
            }
        }

        if(!empty($settings['salePercent'])) {
            $color = $settings['saleColor2'];
            $text = $settings['salePercent'];
            if(empty($oldPrice)) {
                $oldPrice = $price;
            }
            $price = ceil($price*((100 - $settings['salePercent'])/100));
            $salesArr[] = [
                'text' => $text,
                'color' => $color
            ];
        }

        if(!empty($sales)) {
            if(empty($oldPrice)) {
                $oldPrice = $price;
            }
            foreach ($sales as $sale) {
                $percent = (100-(int)$sale['text'])/100;
                $price = $price*$percent;
                $salesArr[] = [
                    'text' => $sale['text'],
                    'color' => $sale['color']
                ];
            }
        }

        return [
            'sale' => $salesArr,
            'price' => ceil($price),
            'oldPrice' => $oldPrice
        ];
    }

    private function productProcessing($products,$blockType,$settings) {
        $productArr = [];
        $counter = 0;

        foreach ($products as $product) {
            $dataProduct = $this->uploadDataToCache($product);
            $link = $product['link'];

            //add coupon to link
            if(!empty($settings['couponName'])) {
                $link = $link.'?coupon='.$settings['couponName'];
            }

            //get add info for sale
            $sales = $product['sale'];
            if(empty($sales)) {
                $sales = [];
            }

            $addInfo = $this->addSales($settings,$dataProduct,$sales);

            if(!empty($addInfo['sale'])) {
                $sales = $addInfo['sale'];
            }

            $oldPrice = $addInfo['oldPrice'];
            $price = $addInfo['price'];
            $productName = $dataProduct['name'];
            $fileName = $dataProduct['filename'];

            //createImage
            if($blockType == 'single-product') {
                $productImg = $this->imageProcessing->createWideImage($fileName);
            }

            if($blockType == 'two-product') {
                $productImg = $this->imageProcessing->createMediumImage($fileName);
            }

            if($blockType == 'two-product-1') {
                $productImg = $this->imageProcessing->createBigImage($fileName);
                if($counter == 1) {
                    $productImg = $this->imageProcessing->createSmallImage($fileName);
                }
            }

            if($blockType == 'two-product-2') {
                $productImg = $this->imageProcessing->createSmallImage($fileName);
                if($counter == 1) {
                    $productImg = $this->imageProcessing->createBigImage($fileName);
                }
            }

            if($blockType == 'three-product') {
                $productImg = $this->imageProcessing->createSmallImage($fileName);
            }

            if(!empty($sales)){
                $productImg = $this->addSaleInfo($sales,$fileName);
            }

            $ver = $this->randGen(9);
            $productImg .= '?ver='.$ver;

            $productArr[] = [
                'name' => $productName,
                'price' => $price,
                'oldPrice' => $oldPrice,
                'img' => $productImg,
                'link' => $link
            ];

            $counter++;
        }

        return $productArr;
    }

    private function randGen(int $num): String {
        $str = '';

        for($i = 0; $i< $num; $i++) {
            $str.= rand(1,9);
        }

        return $str;
    }

}