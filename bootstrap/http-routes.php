<?php

use App\Middlewares\BasicAuthMiddleware;
use App\Pages\AuthPage;
use App\Pages\ConstructorPage;
use App\Pages\GenerateMailPage;
use App\Pages\IndexPage;
use App\Pages\TemplatesPage;
use App\Pages\TemplateViewPage;
use App\Pages\UploadPage;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return static function (App $app): void {
    $app->group('/',function (RouteCollectorProxy $group) {
        $group->get('',[IndexPage::class,'get']);
        $group->get('constructor',[ConstructorPage::class,'get']);
        $group->get('templates',[TemplatesPage::class,'get']);
        $group->get('templates/template/{id}',[TemplatesPage::class,'get']);
        $group->get('templates/views/{id}',[TemplateViewPage::class,'get']);
    })->add(BasicAuthMiddleware::class);

    $app->get('/auth',[AuthPage::class,'get']);
    $app->post('/authData',[AuthPage::class,'authorize']);
    $app->post('/uploadImage',[UploadPage::class,'get']);
    $app->post('/generateMail',[GenerateMailPage::class,'get']);
};