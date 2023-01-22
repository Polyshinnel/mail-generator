<?php


namespace App\Controllers;


class ScreenShooter
{
    public function getScreenShot($id) {
        $protocol = (!empty($_SERVER['HTTPS']) && 'off' !== strtolower($_SERVER['HTTPS'])?"https://":"http://");
        $url = $protocol.$_SERVER['HTTP_HOST'].'/templates/views/'.$id;
        $api_data = file_get_contents("https://www.googleapis.com/pagespeedonline/v2/runPagespeed?url=$url&screenshot=true");
        $api_data = json_decode($api_data, true);
        $screenshot = $api_data['screenshot']['data'];
        $screenshot = str_replace(array('_','-'),array('/','+'),$screenshot);
        return 'data:image/jpeg;base64,'.$screenshot;
    }
}