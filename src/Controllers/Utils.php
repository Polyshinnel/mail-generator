<?php


namespace App\Controllers;


class Utils
{
    public function getCurrUrl(): String {
        $protocol = (!empty($_SERVER['HTTPS']) && 'off' !== strtolower($_SERVER['HTTPS'])?"https://":"http://");
        return $protocol.$_SERVER['HTTP_HOST'];
    }

    function mbUcfirst(String $string): String {
        $string = mb_ereg_replace("^[\ ]+","", $string);
        $string = mb_strtoupper(mb_substr($string, 0, 1, "UTF-8"), "UTF-8").mb_substr($string, 1, mb_strlen($string), "UTF-8" );
        return $string;
    }
}