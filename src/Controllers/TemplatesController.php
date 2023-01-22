<?php


namespace App\Controllers;


use App\Repository\TemplateRepository;

class TemplatesController
{
    private $templateRepository;

    public function __construct(TemplateRepository $templateRepository)
    {
        $this->templateRepository = $templateRepository;
    }

    public function getAllRepository() {
        return $this->templateRepository->getAllTemplates();
    }
}