<?php


namespace App\Controllers;


class ImageProcessing
{
    private $filePath = __DIR__.'/../../public/assets/uploaded/';

    public function createWideImage(String $fileName){
        $maxSize = [
            'width' => 305,
            'height' => 255
        ];

        $imageSize = [
            'width' => 540,
            'height' => 370
        ];

        $this->cropImage($fileName);
        $this->createResampledImage($fileName,$imageSize,$maxSize);
        $this->addGrayFilter($fileName,$imageSize);
        return 'http://'.$_SERVER['HTTP_HOST'].'/assets/uploaded/'.$fileName;
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

        $this->cropImage($fileName);
        $this->createResampledImage($fileName,$imageSize,$maxSize);
        $this->addGrayFilter($fileName,$imageSize);
        return 'http://'.$_SERVER['HTTP_HOST'].'/assets/uploaded/'.$fileName;
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

        $this->cropImage($fileName);
        $this->createResampledImage($fileName,$imageSize,$maxSize);
        $this->addGrayFilter($fileName,$imageSize);
        return 'http://'.$_SERVER['HTTP_HOST'].'/assets/uploaded/'.$fileName;
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

        $this->cropImage($fileName);
        $this->createResampledImage($fileName,$imageSize,$maxSize);
        $this->addGrayFilter($fileName,$imageSize);
        return 'http://'.$_SERVER['HTTP_HOST'].'/assets/uploaded/'.$fileName;
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
        $newImage = imagecreatetruecolor($size['width']*$coefficient,$size['height']*$coefficient);
        $image = imagecreatefromjpeg($path);
        imagecopyresampled($newImage,$image,0,0,0,0,$size['width']*$coefficient,$size['height']*$coefficient,$size['width'],$size['height']);
        imagejpeg($newImage,$path);
        imagedestroy($image);
        imagedestroy($newImage);

        //CreateNewImage
        $imgDest = imagecreatetruecolor($imageSize['width'],$imageSize['height']);
        $white = imagecolorallocate($imgDest,255,255,255);
        imageFilledRectangle($imgDest,0,0,$imageSize['width'],$imageSize['height'],$white);
        $imgSite = imagecreatefromjpeg($path);
        $x = ($imageSize['width'] - ($size['width']*$coefficient))/2;
        $y = ($imageSize['height'] - ($size['height']*$coefficient))/2;
        //Порядок аргументов: куда копируем, что копируем, на сколько двигаем по х, на сколько двигаем по y, ширина и выоста исходного изображения
        imagecopy($imgDest,$imgSite,$x,$y,0,0,$size['width']*$coefficient,$size['height']*$coefficient);
        imagejpeg($imgDest,$path);
        imagedestroy($imgSite);
        imagedestroy($imgDest);
    }

    private function addGrayFilter(String $filename,array $imageSize) {
        $path = $this->filePath.$filename;
        $imgDest = imagecreatetruecolor($imageSize['width'],$imageSize['height']);
        imagesavealpha($imgDest,true);
        $filter = imagecolorallocatealpha($imgDest,0,0,0,122);
        imagefill($imgDest,0,0,$filter);
        $imgSite = imagecreatefromjpeg($path);
        imagecopy($imgSite,$imgDest,0,0,0,0,$imageSize['width'],$imageSize['height']);
        imagejpeg($imgSite,$path);
        imagedestroy($imgSite);
        imagedestroy($imgDest);
    }

    private function cropImage(String $filename) {
        $path = $this->filePath.$filename;
        $img = imagecreatefromjpeg($path);
        $rgb = hexdec('ffffff');
        $cropped = imagecropauto($img,IMG_CROP_THRESHOLD,0.2,$rgb);
        imagejpeg($cropped,$path);
        imagedestroy($img);
        imagedestroy($cropped);
    }
}