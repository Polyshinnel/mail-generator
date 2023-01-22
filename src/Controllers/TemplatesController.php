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
        return $template[0]['json'];
    }

    public function updateImg($id) {
        $img = $this->screenShooter->getScreenShot($id);
        $column = 'img';
        $this->templateRepository->updateTemplateById($id,$img,$column);
    }
}