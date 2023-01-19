<?php

use App\Pages\GenerateMailPage;
use App\Pages\IndexPage;
use App\Pages\UploadPage;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return static function (App $app): void {
    $app->group('/',function (RouteCollectorProxy $group) {
        $group->get('',[IndexPage::class,'get']);
        $group->post('uploadImage',[UploadPage::class,'get']);
        $group->post('generateMail',[GenerateMailPage::class,'get']);
    });
};