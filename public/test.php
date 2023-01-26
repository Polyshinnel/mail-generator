<?php
$url = 'http://mail-generator.web/assets/uploaded/26012023213840.jpg';
$urlArr = explode('/',$url);
$arrLength = count($urlArr);
$fileName = $urlArr[count($urlArr) - 1];
echo $fileName;