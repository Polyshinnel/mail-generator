<?php


namespace App\Controllers;


class UploadImage
{
    public function uploadFile() {
        $uploadDir = __DIR__.'/../../public/assets/uploaded/';
        $fileName = Date('dmYHis').'.jpg';
        $uploadFile = $uploadDir.$fileName;
        move_uploaded_file($_FILES['userFile']['tmp_name'], $uploadFile);
        return $fileName;
    }

    public function uploadFileFromLink($link) {
        $uploadDir = __DIR__.'/../../public/assets/uploaded/';
        $fileName = Date('dmYHis').'.jpg';
        $uploadFile = $uploadDir.$fileName;
        file_put_contents($uploadFile, file_get_contents($link));

        return $fileName;
    }
}