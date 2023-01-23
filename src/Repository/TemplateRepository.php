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
        return $this->templateModel->orderBy('id','DESC')->get()->toArray();
    }

    public function getTemplateById($id) {
        return $this->templateModel->where('id',$id)->first()->toArray();
    }

    public function deleteTemplateById($id):void {
        $this->templateModel::where('id',$id)->delete();
    }

    public function updateTemplateById($id,$data,$column) {
        $updateArr = [
            $column => $data
        ];
        $this->templateModel::where('id',$id)->update($updateArr);
    }

    public function getLastId() {
        $lastTemplate = $this->templateModel->orderBy('id','DESC')->first()->toArray();
        return $lastTemplate['id'];
    }
}