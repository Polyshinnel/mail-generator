<?php


namespace App\Controllers;


use App\Repository\SiteSettingsRepository;
use ZipArchive;

class ZipController
{
    private SiteSettingsRepository $siteSettings;
    private Utils $utils;
    private ZipArchive $zip;

    public function __construct(SiteSettingsRepository $siteSettings,Utils $utils,ZipArchive $zip)
    {
        $this->siteSettings = $siteSettings;
        $this->utils = $utils;
        $this->zip = $zip;
    }

    private function getListFiles(array $json): array {
        $siteName = '';

        //add all images footer
        $imagesList = [
            [
                'path' => __DIR__.'/../../public/assets/images/mailadds/icon_1.jpg',
                'fileName' => 'icon_1.jpg'
            ],
            [
                'path' => __DIR__.'/../../public/assets/images/mailadds/icon_2.jpg',
                'fileName' => 'icon_2.jpg'
            ],
            [
                'path' => __DIR__.'/../../public/assets/images/mailadds/icon_3.jpg',
                'fileName' => 'icon_3.jpg'
            ],
        ];


        foreach ($json as $jsonItem) {
            if($jsonItem['blockName'] == 'header') {
                $siteName = $jsonItem['siteName'];
            }

            if($jsonItem['blockName'] == 'banner') {
                $imgArr = explode('/',$jsonItem['img']);
                $imgName = $imgArr[count($imgArr) - 1];
                $imgPath = __DIR__.'/../../public/assets/uploaded/'.$imgName;
                $imagesList[] = [
                    'path' => $imgPath,
                    'fileName' => $imgName
                ];
            }

            if($jsonItem['blockName'] == 'timer') {
                $imagesList[] = [
                    'path' => __DIR__.'/../../public/assets/images/mailadds/timer.jpg',
                    'fileName' => 'timer.jpg'
                ];
            }

            if(!empty($jsonItem['products'])) {
                $products = $jsonItem['products'];
                foreach ($products as $product) {
                    $link = $product['link'];
                    $imgPath = __DIR__.'/../../public/assets/edited/'.$_SESSION[$link]['filename'];
                    $imagesList[] = [
                        'path' => $imgPath,
                        'fileName' => $_SESSION[$link]['filename']
                    ];
                }
            }
        }

        //get image via site name
        $settings = $this->siteSettings->findSettingsByName($siteName);
        $imgNameArr = explode('/',$settings[0]['logo']);
        $logoName = $imgNameArr[count($imgNameArr) - 1];
        $imagesList[] = [
            'path' => __DIR__.'/../../public/assets/'.$settings[0]['logo'],
            'fileName' => $logoName
        ];

        return $imagesList;
    }

    private function editHtml(String $html): String {
        $htmlLetter = $html;
        $baseUrl = $this->utils->getCurrUrl();
        $imgAddrList = [
            $baseUrl.'/assets/images/logos/',
            $baseUrl.'/assets/images/mailadds/',
            $baseUrl.'/assets/uploaded/',
            $baseUrl.'/assets/edited/',
        ];
        foreach ($imgAddrList as $imgAddr) {
            $htmlLetter = str_replace($imgAddr,'./images/',$htmlLetter);
        }
        $htmlLetter = preg_replace("(\?ver=[0-9]*)", '', $htmlLetter);
        return $htmlLetter;
    }

    private function createLetterFoldersAndCopy(array $json, String $html): void {
        $fileList = $this->getListFiles($json);

        $letterFolder = __DIR__.'/../../public/assets/letter';
        $imagesFolder = __DIR__.'/../../public/assets/letter/images';

        mkdir($letterFolder,0777);
        chmod($letterFolder,0777);
        mkdir($imagesFolder,0777);
        chmod($imagesFolder,0777);

        foreach ($fileList as $fileItem) {
            $destPath = $imagesFolder.'/'.$fileItem['fileName'];
            copy($fileItem['path'],$destPath);
        }

        $letter = $letterFolder.'/index.html';
        $html = $this->editHtml($html);

        file_put_contents($letter,$html);
    }

    private function createZip(array $json) {
        $fileList = $this->getListFiles($json);
        $imagesFolder = __DIR__.'/../../public/assets/letter/images/';
        $letterFile = __DIR__.'/../../public/assets/letter/index.html';
        $zipAddr = __DIR__.'/../../public/assets/letter.zip';
        $this->zip->open($zipAddr,ZipArchive::CREATE|ZipArchive::OVERWRITE);
        $this->zip->addFile($letterFile,'index.html');
        foreach ($fileList as $fileItem) {
            $source = $imagesFolder.$fileItem['fileName'];
            $dest = 'images/'.$fileItem['fileName'];
            $this->zip->addFile($source,$dest);
        }
        $this->zip->close();
    }

    private function removeDir(String $dir): void {
        if ($objs = glob($dir . '/*')) {
            foreach($objs as $obj) {
                is_dir($obj) ? $this->removeDir($obj) : unlink($obj);
            }
        }
        rmdir($dir);
    }

    public function zipLetter(array $json,String $html): void {
        $this->createLetterFoldersAndCopy($json,$html);
        $this->createZip($json);
        $letterFolder = __DIR__.'/../../public/assets/letter';
        $this->removeDir($letterFolder);
    }
}