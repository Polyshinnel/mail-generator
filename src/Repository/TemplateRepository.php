<?php


namespace App\Repository;


use App\Models\Template;

class TemplateRepository
{
    private Template $templateModel;

    public function __construct(Template $templateModel)
    {
        $this->templateModel = $templateModel;
    }

    public function createTemplate(array $createData): void {
        $this->templateModel::create($createData);
    }

    public function getAllTemplates(): ?array {
        return $this->templateModel->orderBy('id','DESC')->get()->toArray();
    }

    public function getTemplateById(int $id): array {
        return $this->templateModel->where('id',$id)->first()->toArray();
    }

    public function deleteTemplateById(int $id): void {
        $this->templateModel::where('id',$id)->delete();
    }

    public function updateTemplateById(int $id,$data,String $column): void {
        $updateArr = [
            $column => $data
        ];
        $this->templateModel::where('id',$id)->update($updateArr);
    }

    public function getLastId(): ?String {
        $lastTemplate = $this->templateModel->orderBy('id','DESC')->first()->toArray();
        return $lastTemplate['id'];
    }
}