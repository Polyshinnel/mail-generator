<?php


namespace App\Controllers;


class MailBlockGenerator
{
    private $siteArr = [
        'Monbento' => [
            'siteName' => 'Monbento',
            'siteAddr' => 'https://monbento.me/',
            'siteAddrShort' => 'monbento.me',
            'logo' => 'images/logos/monbento-logo.png'
        ],
        'Mason Cash' => [
            'siteName' => 'Mason Cash',
            'siteAddr' => 'https://masoncash.me/',
            'siteAddrShort' => 'masoncash.me',
            'logo' => 'images/logos/mason-cash-logo.png'
        ],
        'Paola Reinas' => [
            'siteName' => 'Paola Reinas',
            'siteAddr' => 'https://paolareinas.ru/',
            'siteAddrShort' => 'paolareinas.ru',
            'logo' => 'images/logos/paola-reinas-logo.png'
        ],
    ];

    private $parser;
    private $fileUploader;
    private $imageProcessing;

    public function __construct(SiteParser $parser,UploadImage $fileUploader,ImageProcessing $imageProcessing)
    {
        $this->parser = $parser;
        $this->fileUploader = $fileUploader;
        $this->imageProcessing = $imageProcessing;
    }


    public function createMail($mailBlocks,$siteName) {
        $siteInfo = $this->siteArr[$siteName];

        $mailHead = '
            <html lang="ru">
            
            <head>
              <meta charset="utf-8">
            
              <title>'.$siteInfo['siteName'].'</title>
            </head>
            
            <body
              style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; font-family: Verdana, sans-serif; font-size: 12px; font-weight: normal; line-height: 1; margin: 0; min-width: 100%; padding: 0; width: 100% !important;">
              <div itemscope="" itemtype="http://schema.org/DiscountOffer">
                <meta itemprop="description" content="—30%">
                <meta itemprop="discountCode" content="GIFT2023">
              </div>
              <div itemscope="" itemtype="http://schema.org/EmailMessage">
                <meta itemprop="subjectLine" content="Лучший момент покупать подарки со скидкой на '.$siteInfo['siteAddrShort'].'!">
              </div>
              <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" style="padding: 15px">
                <tr>
                  <td align="center" valign="top">
                    <table border="0" cellspacing="0" cellpadding="0" width="540"
                      style=" padding:0 30px; border: 1px solid #ECECEC; border-radius: 10px;">
                      <tr>
                        <td>
                          <div style="display: none; max-height: 0px; overflow: hidden;">Скидка 30% только по промокоду!</div>
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

    public function createHeader($siteName){
        $protocol = (!empty($_SERVER['HTTPS']) && 'off' !== strtolower($_SERVER['HTTPS'])?"https://":"http://");

        $imgAdd = $protocol.$_SERVER['HTTP_HOST'].'/assets/';
        $siteInfo = $this->siteArr[$siteName];
        return '<tr>
            <td>
              <table border="0" cellspacing="0" cellpadding="0" width="100%" class="header"
                style="color: #B2B2B2; font-size: 11px; letter-spacing: -0.7px; padding: 4px 0 14px;">
                <tr valign="top">
                  <td align="left" width="150">
                    У вас отличный вкус!
                  </td>
                  <td align="center" class="logo" style="padding-top: 8px; padding-bottom: 8px;">
                    <a href="'.$siteInfo['siteAddr'].'">
                      <img src="'.$imgAdd.$siteInfo['logo'].'" alt="'.$siteName.'" width="242" height="39">
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

    public function createFooter($siteName,$delivery,$discount){
        $protocol = (!empty($_SERVER['HTTPS']) && 'off' !== strtolower($_SERVER['HTTPS'])?"https://":"http://");
        $imgAdd = $protocol.$_SERVER['HTTP_HOST'].'/assets/';

        $siteInfo = $this->siteArr[$siteName];
        return '<tr align="center" valign="top" class="copyright"
            style="font-size: 12px; line-height: 25px; text-align: center;">
            <td style="padding: 75px 0 25px;">
              <a href="'.$siteInfo['siteAddr'].'"
                style="color: #2E78E8; text-decoration: none;">'.$siteInfo['siteAddrShort'].'</a> © 2022. Есть вопросы? Звоните — 8 800
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
                          <a href="'.$delivery.'"
                            style="color: #000000; text-decoration: none;">
                            <img src="'.$imgAdd.'images/mailadds/icon_1.jpg" alt="Доставка" width="64" height="64">
                          </a>
                        </td>
                      </tr>
                      <tr valign="top">
                        <td class="footer__table__text"
                          style="color: #000000; font-size: 12px; height: 84px; line-height: 20px;">
                          <a href="'.$delivery.'"
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
                          <a href="'.$siteInfo['siteAddr'].'" style="color: #000000; text-decoration: none;">
                            <img src="'.$imgAdd.'images/mailadds/icon_2.jpg" alt="Магазин" width="55" height="55">
                          </a>
                        </td>
                      </tr>
                      <tr valign="top">
                        <td class="footer__table__text"
                          style="color: #000000; font-size: 12px; height: 84px; line-height: 20px;">
                          <a href="https://monbento.me" style="color: #000000; text-decoration: none;">
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
                          <a href="'.$discount.'" style="color: #000000; text-decoration: none;">
                            <img src="'.$imgAdd.'images/mailadds/icon_3.jpg" alt="Скидки" width="54" height="54">
                          </a>
                        </td>
                      </tr>
                      <tr valign="top">
                        <td class="footer__table__text"
                          style="color: #000000; font-size: 12px; height: 84px; line-height: 20px;">
                          <a href="'.$discount.'" style="color: #000000; text-decoration: none;">
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

    public function createBanner($imgLink,$bannerLink,$name){
        return '<tr align="center" valign="top">
            <td style="padding-bottom:0;">
              <a href="'.$bannerLink.'" style="text-decoration: none;">
                <img src="'.$imgLink.'" alt="'.$name.'"
                  width="540" height="706">
              </a>
            </td>
          </tr>';
    }

    public function createTimer(){
        $protocol = (!empty($_SERVER['HTTPS']) && 'off' !== strtolower($_SERVER['HTTPS'])?"https://":"http://");
        $imgAdd = $protocol.$_SERVER['HTTP_HOST'].'/assets/';
        return '<tr>
            <td align="center">
              <img src="'.$imgAdd.'images/mailadds/timer.jpg" width="540" height="87" />
            </td>
          </tr>';
    }

    public function createProductsBlock($productsBlock) {
        $nameBlock =  $productsBlock['blockName'];
        $blockData = '';

        if($nameBlock == 'single-product') {
            $product = $productsBlock['products'][0];
            $dataProduct = $this->uploadDataToCache($product);

            $link = $product['link'];
            $price = $product['price'];
            $newPrice = $product['newPrice'];
            $productName = $dataProduct['name'];
            $fileName = $dataProduct['filename'];
            $productImg = $this->imageProcessing->createWideImage($fileName);

            if(!empty($product['sale'])){
                $salesArr = $product['sale'];
                for($i = 0; $i < count($salesArr);$i++){
                    $offset = $i*62;
                    $color = $salesArr[$i]['color'];
                    $text = $salesArr[$i]['text'];
                    $productImg = $this->imageProcessing->addSaleBlock($offset,$fileName,$text,$color);
                }
            }

            $productImg .= '?ver='.time();

            $blockData = '<table width="100%" border="0" cellpadding="0" cellspacing="0" style="padding: 13px 0;">
                <tr valign="middle">
                  <td align="center" style="background-color: #F5F5F5">
                    <a href="'.$link.'"
                      style="text-decoration: none;">
                      <img src="'.$productImg.'" alt="'.$productName.'" width="540"
                        height="370">
                      <div style="text-align: left; padding: 0 24px 24px">
                        <div style="font-size: 12px;line-height: 23px;">
                          <span style="color: #39B54A;">'.$newPrice.' руб.</span>
                          <s style="color: #000;">'.$price.' руб.</s>
                        </div>
                        <span class="product__title"
                          style="color: #000000; font-size: 12px; line-height: 23px; padding-right: 10px;">
                          '.$productName.'
                        </span>
                      </div>
                    </a>
                  </td>
                </tr>
              </table>';
        }

        if($nameBlock == 'two-product') {
            $productsArr = [];
            $products = $productsBlock['products'];
            foreach ($products as $product) {
                $link = $product['link'];
                $price = $product['price'];
                $newPrice = $product['newPrice'];

                $dataProduct = $this->uploadDataToCache($product);
                $productName = $dataProduct['name'];
                $fileName = $dataProduct['filename'];


                $productImg = $this->imageProcessing->createMediumImage($fileName);

                if(!empty($product['sale'])){
                    $salesArr = $product['sale'];
                    for($i = 0; $i < count($salesArr);$i++){
                        $offset = $i*62;
                        $color = $salesArr[$i]['color'];
                        $text = $salesArr[$i]['text'];
                        $productImg = $this->imageProcessing->addSaleBlock($offset,$fileName,$text,$color);
                    }
                }

                $productImg .= '?ver='.time();

                $productsArr[] = [
                    'name' => $productName,
                    'link' => $link,
                    'price' => $price,
                    'newPrice' => $newPrice,
                    'img' => $productImg
                ];
            }

            $blockData = '<table width="100%" border="0" cellpadding="0" cellspacing="0" style="padding: 13px 0;">
                <tr valign="top">
                  <td align="left">
                    <div style="text-align: left; width: 256px;">
                      <a href="'.$productsArr[0]['link'].'"
                        class="product" style="text-decoration: none;">
                        <img src="'.$productsArr[0]['img'].'" alt="'.$productsArr[0]['name'].'" width="256"
                          height="255" style="margin-bottom: 13px;">
                        <div style="font-size: 12px;line-height: 23px;">
                          <span style="color: #39B54A;">'.$productsArr[0]['newPrice'].' руб.</span>
                          <s style="color: #000;">'.$productsArr[0]['price'].' руб.</s>
                        </div>
                        <span class="product__title"
                          style="color: #000000; font-size: 12px; line-height: 23px; padding-right: 10px;">
                          '.$productsArr[0]['name'].'
                        </span>
                      </a>
                    </div>
                  </td>

                  <td align="right">
                    <div style="text-align: left; width: 256px;">
                      <a href="'.$productsArr[1]['link'].'"
                        class="product" style="text-decoration: none;">
                        <img src="'.$productsArr[1]['img'].'" alt="'.$productsArr[1]['name'].'" width="256"
                          height="255" style="margin-bottom: 13px;">
                        <div style="font-size: 12px;line-height: 23px;">
                          <span style="color: #39B54A;">'.$productsArr[1]['newPrice'].' руб.</span>
                          <s style="color: #000;">'.$productsArr[1]['price'].' руб.</s>
                        </div>
                        <span class="product__title"
                          style="color: #000000; font-size: 12px; line-height: 23px; padding-right: 10px;">
                          '.$productsArr[1]['name'].'
                        </span>
                      </a>
                    </div>
                  </td>
                </tr>
              </table>';
        }

        if($nameBlock == 'two-product-1') {
            $productsArr = [];
            $products = $productsBlock['products'];
            foreach ($products as $product) {
                $link = $product['link'];
                $price = $product['price'];
                $newPrice = $product['newPrice'];


                $dataProduct = $this->uploadDataToCache($product);
                $productName = $dataProduct['name'];
                $fileName = $dataProduct['filename'];
                $sale = '';
                if(!empty($product['sale'])) {
                    $sale = $product['sale'];
                }

                $productsArr[] = [
                    'name' => $productName,
                    'link' => $link,
                    'price' => $price,
                    'newPrice' => $newPrice,
                    'filename' => $fileName,
                    'sale' => $sale
                ];
            }

            $imgOne = $this->imageProcessing->createBigImage($productsArr[0]['filename']);
            if(!empty($productsArr[0]['sale'])){
                $salesArr = $productsArr[0]['sale'];
                $fileName = $productsArr[0]['filename'];
                for($i = 0; $i < count($salesArr);$i++){
                    $offset = $i*62;
                    $color = $salesArr[$i]['color'];
                    $text = $salesArr[$i]['text'];
                    $imgOne = $this->imageProcessing->addSaleBlock($offset,$fileName,$text,$color);
                }
            }

            $imgOne .= '?ver='.time();

            $imgTwo = $this->imageProcessing->createSmallImage($productsArr[1]['filename']);

            if(!empty($productsArr[1]['sale'])){
                $salesArr = $productsArr[1]['sale'];
                $fileName = $productsArr[1]['filename'];

                for($i = 0; $i < count($salesArr);$i++){
                    $offset = $i*62;
                    $color = $salesArr[$i]['color'];
                    $text = $salesArr[$i]['text'];
                    $imgTwo = $this->imageProcessing->addSaleBlock($offset,$fileName,$text,$color);
                }
            }

            $imgTwo .= '?ver='.time();

            $blockData = '<table width="100%" border="0" cellpadding="0" cellspacing="0" style="padding: 13px 0;">
                <tr valign="middle">
                  <td align="left">
                    <div style="text-align: left; width: 351px;">
                      <a href="'.$productsArr[0]['link'].'"
                        class="product" style="text-decoration: none;">
                        <img src="'.$imgOne.'" alt="'.$productsArr[0]['name'].'" width="351"
                          height="350" style="margin-bottom: 13px;">
                        <div style="font-size: 12px;line-height: 23px;">
                          <span style="color: #39B54A;">'.$productsArr[0]['newPrice'].' руб.</span>
                          <s style="color: #000;">'.$productsArr[0]['price'].' руб.</s>
                        </div>
                        <span class="product__title"
                          style="color: #000000; font-size: 12px; line-height: 23px; padding-right: 10px;">
                          '.$productsArr[0]['name'].'
                        </span>
                      </a>
                    </div>
                  </td>
                  <td align="right">
                    <div style="text-align: left; width: 162px;">
                      <a href="'.$productsArr[1]['link'].'"
                        class="product" style="text-decoration: none;">
                        <img src="'.$imgTwo.'" alt="'.$productsArr[1]['name'].'" width="162"
                          height="162" style="margin-bottom: 13px;">
                        <div style="font-size: 12px;line-height: 23px;">
                          <span style="color: #39B54A;">'.$productsArr[1]['newPrice'].' руб.</span>
                          <s style="color: #000;">'.$productsArr[1]['price'].' руб.</s>
                        </div>
                        <span class="product__title"
                          style="color: #000000; font-size: 12px; line-height: 23px; padding-right: 10px;">
                          '.$productsArr[1]['name'].'
                        </span>
                      </a>
                    </div>
                  </td>
                </tr>
              </table>';
        }

        if($nameBlock == 'two-product-2') {
            $productsArr = [];
            $products = $productsBlock['products'];
            foreach ($products as $product) {
                $link = $product['link'];
                $price = $product['price'];
                $newPrice = $product['newPrice'];

                $dataProduct = $this->uploadDataToCache($product);
                $productName = $dataProduct['name'];
                $fileName = $dataProduct['filename'];
                $sale = '';
                if(!empty($product['sale'])) {
                    $sale = $product['sale'];
                }

                $productsArr[] = [
                    'name' => $productName,
                    'link' => $link,
                    'price' => $price,
                    'newPrice' => $newPrice,
                    'filename' => $fileName,
                    'sale' => $sale
                ];
            }

            $imgOne = $this->imageProcessing->createSmallImage($productsArr[0]['filename']);

            if(!empty($productsArr[0]['sale'])){
                $salesArr = $productsArr[0]['sale'];
                $fileName = $productsArr[0]['filename'];

                for($i = 0; $i < count($salesArr);$i++){
                    $offset = $i*62;
                    $color = $salesArr[$i]['color'];
                    $text = $salesArr[$i]['text'];
                    $imgOne = $this->imageProcessing->addSaleBlock($offset,$fileName,$text,$color);
                }
            }

            $imgOne .= '?ver='.time();

            $imgTwo = $this->imageProcessing->createBigImage($productsArr[1]['filename']);

            if(!empty($productsArr[1]['sale'])){
                $salesArr = $productsArr[1]['sale'];
                $fileName = $productsArr[1]['filename'];

                for($i = 0; $i < count($salesArr);$i++){
                    $offset = $i*62;
                    $color = $salesArr[$i]['color'];
                    $text = $salesArr[$i]['text'];
                    $imgTwo = $this->imageProcessing->addSaleBlock($offset,$fileName,$text,$color);
                }
            }

            $imgTwo .= '?ver='.time();


            $blockData = '<table width="100%" border="0" cellpadding="0" cellspacing="0" style="padding: 13px 0;">
                <tr valign="middle">
                  <td align="left">
                    <div style="text-align: left; width: 162px;">
                      <a href="'.$productsArr[0]['link'].'"
                        class="product" style="text-decoration: none;">
                        <img src="'.$imgOne.'" alt="'.$productsArr[0]['name'].'" width="162"
                          height="162" style="margin-bottom: 13px;">
                        <div style="font-size: 12px;line-height: 23px;">
                          <span style="color: #39B54A;">'.$productsArr[0]['newPrice'].' руб.</span>
                          <s style="color: #000;">'.$productsArr[0]['price'].' руб.</s>
                        </div>
                        <span class="product__title"
                          style="color: #000000; font-size: 12px; line-height: 23px; padding-right: 10px;">
                          '.$productsArr[0]['name'].'
                        </span>
                      </a>
                    </div>
                  </td>
                  <td align="right">
                    <div style="text-align: left; width: 351px;">
                      <a href="'.$productsArr[1]['link'].'"
                        class="product" style="text-decoration: none;">
                        <img src="'.$imgTwo.'" alt="'.$productsArr[1]['name'].'" width="351"
                          height="350" style="margin-bottom: 13px;">
                        <div style="font-size: 12px;line-height: 23px;">
                          <span style="color: #39B54A;">'.$productsArr[1]['newPrice'].' руб.</span>
                          <s style="color: #000;">'.$productsArr[1]['price'].' руб.</s>
                        </div>
                        <span class="product__title"
                          style="color: #000000; font-size: 12px; line-height: 23px; padding-right: 10px;">
                          '.$productsArr[1]['name'].'
                        </span>
                      </a>
                    </div>
                  </td>
                </tr>
              </table>';
        }

        if($nameBlock == 'three-product') {
            $productsArr = [];
            $products = $productsBlock['products'];
            foreach ($products as $product) {
                $link = $product['link'];
                $price = $product['price'];
                $newPrice = $product['newPrice'];

                $dataProduct = $this->uploadDataToCache($product);
                $productName = $dataProduct['name'];
                $fileName = $dataProduct['filename'];

                $productImg = $this->imageProcessing->createSmallImage($fileName);

                if(!empty($product['sale'])){
                    $salesArr = $product['sale'];
                    for($i = 0; $i < count($salesArr);$i++){
                        $offset = $i*62;
                        $color = $salesArr[$i]['color'];
                        $text = $salesArr[$i]['text'];
                        $productImg = $this->imageProcessing->addSaleBlock($offset,$fileName,$text,$color);
                    }
                }

                $productImg .= '?ver='.time();

                $productsArr[] = [
                    'name' => $productName,
                    'link' => $link,
                    'price' => $price,
                    'newPrice' => $newPrice,
                    'img' => $productImg
                ];
            }

            $blockData = '<table width="100%" border="0" cellpadding="0" cellspacing="0" style="padding: 13px 0;">
                <tr valign="top">
                  <td align="left">
                    <div style="text-align: left; width: 162px;">
                      <a href="'.$productsArr[0]['link'].'"
                        class="product" style="text-decoration: none;">
                        <img src="'.$productsArr[0]['img'].'" alt="'.$productsArr[0]['name'].'" width="162"
                          height="162" style="margin-bottom: 13px;">
                        <div style="font-size: 12px;line-height: 23px;">
                          <span style="color: #39B54A;">'.$productsArr[0]['newPrice'].' руб.</span>
                          <s style="color: #000;">'.$productsArr[0]['price'].' руб.</s>
                        </div>
                        <span class="product__title"
                          style="color: #000000; font-size: 12px; line-height: 23px; padding-right: 10px;">
                          '.$productsArr[0]['name'].'
                        </span>
                      </a>
                    </div>
                  </td>
                  <td align="center">
                    <div style="text-align: left; width: 162px;">
                      <a href="'.$productsArr[1]['link'].'"
                        class="product" style="text-decoration: none;">
                        <img src="'.$productsArr[1]['img'].'" alt="'.$productsArr[1]['name'].'" width="162"
                          height="162" style="margin-bottom: 13px;">
                        <div style="font-size: 12px;line-height: 23px;">
                          <span style="color: #39B54A;">'.$productsArr[1]['newPrice'].' руб.</span>
                          <s style="color: #000;">'.$productsArr[1]['price'].' руб.</s>
                        </div>
                        <span class="product__title"
                          style="color: #000000; font-size: 12px; line-height: 23px; padding-right: 10px;">
                          '.$productsArr[1]['name'].'
                        </span>
                      </a>
                    </div>
                  </td>
                  <td align="right">
                    <div style="text-align: left; width: 162px;">
                      <a href="'.$productsArr[2]['link'].'"
                        class="product" style="text-decoration: none;">
                        <img src="'.$productsArr[2]['img'].'" alt="'.$productsArr[2]['name'].'" width="162"
                          height="162" style="margin-bottom: 13px;">
                        <div style="font-size: 12px;line-height: 23px;">
                          <span style="color: #39B54A;">'.$productsArr[2]['newPrice'].' руб.</span>
                          <s style="color: #000;">'.$productsArr[2]['price'].' руб.</s>
                        </div>
                        <span class="product__title"
                          style="color: #000000; font-size: 12px; line-height: 23px; padding-right: 10px;">
                          '.$productsArr[2]['name'].'
                        </span>
                      </a>
                    </div>
                  </td>
                </tr>
              </table>';
        }

        return '<tr align="center" valign="top"><td>'.$blockData.'</td></tr>';
    }

    public function createCommonBanner($imgLink,$bannerLink,$name) {
        $blockData = '<table width="100%" border="0" cellpadding="0" cellspacing="0" style="padding: 13px 0;">
                <tr valign="middle">
                  <td align="center">
                    <a href="'.$bannerLink.'"
                      style="text-decoration: none;">
                      <img src="'.$imgLink.'" alt="'.$name.'" width="540" height="407">
                    </a>
                  </td>
                </tr>
              </table>';
        return '<tr align="center" valign="top"><td>'.$blockData.'</td></tr>';
    }

    private function uploadDataToCache($productItem) {
        $link = $productItem['link'];

        if(!empty($_SESSION[$link])) {
            return $_SESSION[$link];
        }

        $productInfo = $this->parser->getMonbentoData($link);
        $fileName = $this->fileUploader->uploadFileFromLink($productInfo['productImg']);

        $_SESSION[$link] = [
            'link' => $link,
            'name' => $productInfo['productName'],
            'filename' => $fileName
        ];


        return [
            'link' => $link,
            'name' => $productInfo['productName'],
            'filename' => $fileName
        ];
    }

}