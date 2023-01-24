<?php


namespace App\Controllers;


use Screen\Capture;

class ScreenShooter
{
    private $screenCapture;

    public function __construct(Capture $screenCapture)
    {
        $this->screenCapture = $screenCapture;
    }

    public function getScreenShot($id) {
        $protocol = (!empty($_SERVER['HTTPS']) && 'off' !== strtolower($_SERVER['HTTPS'])?"https://":"http://");
        $url = $protocol.$_SERVER['HTTP_HOST'].'/templates/views/'.$id;

        $filename = time();
        $screenPath = __DIR__.'/../../public/assets/screenshot/'.$filename;

        $this->screenCapture->setOptions([
            'ignore-ssl-errors' => 'yes',
            'ssl-protocol' => "any",
            'web-security' => "true"
        ]);
        $this->screenCapture->setUrl($url);
        $this->screenCapture->setWidth(1000);
        $this->screenCapture->setHeight(1000);
        $this->screenCapture->setClipWidth(1000);
        $this->screenCapture->setClipHeight(1000);
        $this->screenCapture->save($screenPath);

        return '/assets/screenshot/'.$filename.'.jpg';
    }
}