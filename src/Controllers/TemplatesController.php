<?php


namespace App\Controllers;


use App\Repository\TemplateRepository;

class TemplatesController
{
    private $templateRepository;
    private $screenShooter;

    public function __construct(TemplateRepository $templateRepository,ScreenShooter $screenShooter)
    {
        $this->templateRepository = $templateRepository;
        $this->screenShooter = $screenShooter;
    }

    public function getAllRepository() {
        return $this->templateRepository->getAllTemplates();
    }

    public function getTemplateViewById($id) {
        $template = $this->templateRepository->getTemplateById($id);
        return $template['json'];
    }

    public function getTemplateById($id) {
        return $this->templateRepository->getTemplateById($id);
    }

    private function updateImg($id) {
        $img = $this->screenShooter->getScreenShot($id);
        $column = 'img';
        $this->templateRepository->updateTemplateById($id,$img,$column);
    }

    public function createTemplate($json,$name = NULL) {
        $allTemp = $this->templateRepository->getAllTemplates();
        $tempName = $name;

        if($name == '') {
            $num = 1;
            if(!empty($allTemp)) {
                $lastTemp = $allTemp[0];
                $name = $lastTemp['name'];
                $nameArr = explode(' ',$name);
                $num = $nameArr[1]+1;
            }

            $tempName = 'Шаблон '.$num;
        }



        $createArr = [
            'name' => $tempName,
            'img' => '',
            'date_create' => date("Y-m-d"),
            'json' => $json
        ];
        $this->templateRepository->createTemplate($createArr);
        $id = $this->templateRepository->getLastId();
        $this->updateImg($id);
    }
}