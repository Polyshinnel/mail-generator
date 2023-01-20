<?php


namespace App\Controllers;


class ImageProcessing
{
    private $filePath = __DIR__.'/../../public/assets/uploaded/';
    private $editedPath = __DIR__.'/../../public/assets/edited/';

    public function createWideImage(String $fileName){
        $maxSize = [
            'width' => 305,
            'height' => 255
        ];

        $imageSize = [
            'width' => 540,
            'height' => 370
        ];

        return $this->finishedImage($fileName,$imageSize,$maxSize);
    }

    public function createBigImage($fileName) {
        $maxSize = [
            'width' => 260,
            'height' => 260
        ];

        $imageSize = [
            'width' => 350,
            'height' => 350
        ];

        return $this->finishedImage($fileName,$imageSize,$maxSize);
    }

    public function createMediumImage($fileName){
        $maxSize = [
            'width' => 190,
            'height' => 190
        ];

        $imageSize = [
            'width' => 256,
            'height' => 256
        ];

        return $this->finishedImage($fileName,$imageSize,$maxSize);
    }

    public function createSmallImage($fileName){
        $maxSize = [
            'width' => 120,
            'height' => 120
        ];

        $imageSize = [
            'width' => 162,
            'height' => 162
        ];

        return $this->finishedImage($fileName,$imageSize,$maxSize);
    }

    private function getImageSize(String $filename) {
        $path = $this->filePath.$filename;
        $size = getimagesize($path);
        return [
            'width' => $size[0],
            'height' => $size[1]
        ];
    }

    private function getCoefficient(array $size,array $maxSize){
        $width = $size['width'];
        $height = $size['height'];
        $coefficientPercent = 100;

        if($width > $height) {
            $coefficientPercent = ceil(($maxSize['width']/$width)*100);
        } else {
            $coefficientPercent = ceil(($maxSize['height']/$height)*100);
        }

        return $coefficientPercent/100;
    }

    private function createResampledImage(String $filename,array $imageSize,array $maxSize){
        $size = $this->getImageSize($filename);

        $coefficient = $this->getCoefficient($size,$maxSize);


        //Resized image
        $path = $this->filePath.$filename;
        $editedPath = $this->editedPath.$filename;
        $newImage = imagecreatetruecolor($size['width']*$coefficient,$size['height']*$coefficient);
        $image = imagecreatefrompng($path);
        imagecopyresampled($newImage,$image,0,0,0,0,$size['width']*$coefficient,$size['height']*$coefficient,$size['width'],$size['height']);
        imagepng($newImage,$editedPath);
        imagedestroy($image);
        imagedestroy($newImage);

        //CreateNewImage
        $imgDest = imagecreatetruecolor($imageSize['width'],$imageSize['height']);
        $white = imagecolorallocate($imgDest,255,255,255);
        imageFilledRectangle($imgDest,0,0,$imageSize['width'],$imageSize['height'],$white);
        $imgSite = imagecreatefrompng($editedPath);
        $x = ($imageSize['width'] - ($size['width']*$coefficient))/2;
        $y = ($imageSize['height'] - ($size['height']*$coefficient))/2;
        //Порядок аргументов: куда копируем, что копируем, на сколько двигаем по х, на сколько двигаем по y, ширина и выоста исходного изображения
        imagecopy($imgDest,$imgSite,$x,$y,0,0,$size['width']*$coefficient,$size['height']*$coefficient);
        imagepng($imgDest,$editedPath);
        imagedestroy($imgSite);
        imagedestroy($imgDest);
    }

    private function addGrayFilter(String $filename,array $imageSize) {
        $path = $this->editedPath.$filename;
        $imgDest = imagecreatetruecolor($imageSize['width'],$imageSize['height']);
        imagesavealpha($imgDest,true);
        $filter = imagecolorallocatealpha($imgDest,0,0,0,122);
        imagefill($imgDest,0,0,$filter);
        $imgSite = imagecreatefrompng($path);
        imagecopy($imgSite,$imgDest,0,0,0,0,$imageSize['width'],$imageSize['height']);
        imagepng($imgSite,$path);
        imagedestroy($imgSite);
        imagedestroy($imgDest);
    }

    public function cropImage(String $filename) {
        $path = $this->filePath.$filename;
        $img = imagecreatefromjpeg($path);
        $rgb = hexdec('ffffff');
        $cropped = imagecropauto($img,IMG_CROP_THRESHOLD,0.2,$rgb);
        imagepng($cropped,$path);
        imagedestroy($img);
        imagedestroy($cropped);
    }

    private function finishedImage(String $fileName,array $imageSize,array $maxSize) {
        $this->createResampledImage($fileName,$imageSize,$maxSize);
        $this->addGrayFilter($fileName,$imageSize);
        $protocol = (!empty($_SERVER['HTTPS']) && 'off' !== strtolower($_SERVER['HTTPS'])?"https://":"http://");
        return $protocol.$_SERVER['HTTP_HOST'].'/assets/edited/'.$fileName;
    }

    private function hexToRgb(String $color) {
        $rgb = sscanf($color, "#%02x%02x%02x");
        return [
            'red' => $rgb[0],
            'green' => $rgb[1],
            'blue' => $rgb[2]
        ];
    }

    public function addSaleBlock(int $offset,String $filename,String $text,String $color) {
        //Get Font
        $font = __DIR__.'/../../public/assets/fonts/DecimaMonoPro-Bold.ttf';
        //Get Path to IMG where add Sale Block
        $path = $this->editedPath.$filename;
        //Params Sale Block
        $width = 62;
        $height = 30;

        //Create Sale Block
        $saleBlock = imagecreatetruecolor($width,$height);
        $rgb = $this->hexToRgb($color);
        $colorBackground = imagecolorallocate($saleBlock,$rgb['red'],$rgb['green'],$rgb['blue']);
        imagefill($saleBlock,0,0,$colorBackground);


        //Add text to Sale Block
        $whiteColor = imagecolorallocate($saleBlock,255,255,255);
        ImageTTFtext($saleBlock,14,0,7,22,$whiteColor,$font,$text);

        //Add Sale block to target Img
        $img = imagecreatefrompng($path);
        imagecopy($img,$saleBlock,$offset,0,0,0,$width,$height);
        imagepng($img,$path);
        imagedestroy($saleBlock);
        imagedestroy($img);

        $protocol = (!empty($_SERVER['HTTPS']) && 'off' !== strtolower($_SERVER['HTTPS'])?"https://":"http://");
        return $protocol.$_SERVER['HTTP_HOST'].'/assets/edited/'.$filename;
    }

}