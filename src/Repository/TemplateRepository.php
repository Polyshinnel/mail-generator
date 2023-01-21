<?php


namespace App\Repository;


use App\Models\Template;

class TemplateRepository
{
    private $templateModel;

    public function __construct(Template $templateModel)
    {
        $this->templateModel = $templateModel;
    }

    public function createTemplate(array $createData): void {
        $this->templateModel::create($createData);
    }

    public function getAllTemplates() {
        return $this->templateModel->all()->toArray();
    }

    public function getTemplateById($id) {
        return $this->templateModel->where('id',$id)->get()->toArray();
    }

    public function deleteTemplateById($id):void {
        $this->templateModel::where('id',$id)->delete();
    }
}