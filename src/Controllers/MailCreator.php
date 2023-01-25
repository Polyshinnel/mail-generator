<?php


namespace App\Controllers;


class MailCreator
{
    private $mailBlockGenerator;

    public function __construct(MailBlockGenerator $mailBlockGenerator)
    {
        $this->mailBlockGenerator = $mailBlockGenerator;
    }

    public function finalCreateMail(array $jsonArr) {
        $mailBlocks = '';


        foreach ($jsonArr as $jsonItem) {
            $nameBlock = $jsonItem['blockName'];

            if($nameBlock == 'header'){
                $siteName = $jsonItem['siteName'];
                $commonSiteName = $siteName;
                $mailBlocks .= $this->mailBlockGenerator->createHeader($siteName);
            }

            if($nameBlock == 'banner'){
                $alt = $jsonItem['alt'];
                $link = $jsonItem['link'];
                $img = $jsonItem['img'];
                $mailBlocks .= $this->mailBlockGenerator->createBanner($img,$link,$alt);
            }

            if($nameBlock == 'banner-common'){
                $alt = $jsonItem['alt'];
                $link = $jsonItem['link'];
                $img = $jsonItem['img'];
                $mailBlocks .= $this->mailBlockGenerator->createCommonBanner($img,$link,$alt);
            }

            if($nameBlock == 'timer'){
                $mailBlocks .= $this->mailBlockGenerator->createTimer();
            }

            if($nameBlock == 'footer'){
                $siteName = $jsonItem['siteName'];
                $mailBlocks .= $this->mailBlockGenerator->createFooter($siteName);
            }

            if($nameBlock == 'single-product' || 'two-product' || 'two-product-1' || 'two-product-2' || 'three-product') {
                $mailBlocks .= $this->mailBlockGenerator->createProductsBlock($jsonItem);
            }
        }


        return $mailBlocks;
    }

    public function getSiteName(array $jsonArr) {
        $commonSiteName = 'Monbento';

        foreach ($jsonArr as $jsonItem) {
            $nameBlock = $jsonItem['blockName'];

            if($nameBlock == 'header'){
                $siteName = $jsonItem['siteName'];
                $commonSiteName = $siteName;
            }
        }

        return $commonSiteName;
    }
}