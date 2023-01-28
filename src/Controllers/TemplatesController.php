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

    public function getTemplateViewById(int $id): array {
        return $this->templateRepository->getTemplateById($id);
    }

    public function getTemplateById(int $id): array {
        return $this->templateRepository->getTemplateById($id);
    }

    private function updateImg(int $id): void {
        $img = $this->screenShooter->getScreenShot($id);
        $column = 'img';
        $this->templateRepository->updateTemplateById($id,$img,$column);
    }

    public function createTemplate(String $json,String $html,String $name = NULL): void{
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

        //Encode HTML to save in DB
        $html = htmlspecialchars($html);

        $createArr = [
            'name' => $tempName,
            'img' => '',
            'date_create' => date("Y-m-d"),
            'json' => $json,
            'html' => $html
        ];
        $this->templateRepository->createTemplate($createArr);
        $id = $this->templateRepository->getLastId();
        $this->updateImg($id);
    }

    public function updateTemplate(int $id,String $json,String $html,String $name): void {
        $html = htmlspecialchars($html);
        $this->templateRepository->updateTemplateById($id,$json,'json');
        $this->templateRepository->updateTemplateById($id,$name,'name');
        $this->templateRepository->updateTemplateById($id,$html,'html');
        $this->updateImg($id);
    }

    public function deleteTemplate(int $id): void {
        $this->templateRepository->deleteTemplateById($id);
    }
}