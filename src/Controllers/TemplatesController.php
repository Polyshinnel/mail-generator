<?php


namespace App\Controllers;


use App\Repository\TemplateRepository;

class TemplatesController
{
    private TemplateRepository $templateRepository;
    private ScreenShooter $screenShooter;

    public function __construct(TemplateRepository $templateRepository,ScreenShooter $screenShooter)
    {
        $this->templateRepository = $templateRepository;
        $this->screenShooter = $screenShooter;
    }

    public function getAllRepository(): ?array {
        return $this->templateRepository->getAllTemplates();
    }

    public function getTemplateViewById(int $id): String {
        $template = $this->templateRepository->getTemplateById($id);
        return $template['json'];
    }

    public function getTemplateById(int $id): array {
        return $this->templateRepository->getTemplateById($id);
    }

    private function updateImg(int $id): void {
        $img = $this->screenShooter->getScreenShot($id);
        $column = 'img';
        $this->templateRepository->updateTemplateById($id,$img,$column);
    }

    public function createTemplate(String $json,String $name = NULL): void{
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

    public function updateTemplate(int $id,String $json,String $name): void {
        $this->templateRepository->updateTemplateById($id,$json,'json');
        $this->templateRepository->updateTemplateById($id,$name,'name');
        $this->updateImg($id);
    }

    public function deleteTemplate(int $id): void {
        $this->templateRepository->deleteTemplateById($id);
    }
}