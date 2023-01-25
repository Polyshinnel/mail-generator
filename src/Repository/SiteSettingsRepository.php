<?php


namespace App\Repository;


use App\Models\SiteSettings;

class SiteSettingsRepository
{
    private SiteSettings $siteSettingsModel;

    public function __construct(SiteSettings $siteSettingsModel)
    {
        $this->siteSettingsModel = $siteSettingsModel;
    }

    public function createSettings(array $createArr): void {
        $this->siteSettingsModel::create($createArr);
    }

    public function findSettingsByName(String $siteName): ?array {
        $filterArr = [
            'name' => $siteName
        ];
        return $this->siteSettingsModel->where($filterArr)->get()->toArray();
    }

    public function findSettingsByHost(String $host): ?array {
        $filterArr = [
            'site_addr_short' => $host
        ];

        return $this->siteSettingsModel->where($filterArr)->get()->toArray();
    }

    public function updateSettingsById(int $id,array $params): void {
        $this->siteSettingsModel->where('id',$id)->update($params);
    }

    public function deleteSettingsById(int $id): void {
        $this->siteSettingsModel->where('id',$id)->delete();
    }
}