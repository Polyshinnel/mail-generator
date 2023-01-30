<?php

use App\Middlewares\BasicAuthMiddleware;
use App\Pages\AuthPage;
use App\Pages\ClearSession;
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
        $group->get('templates/template/{id}',[TemplatesPage::class,'getTemplate']);
        $group->get('templates/zip/{id}',[TemplatesPage::class,'getTemplateZip']);

    })->add(BasicAuthMiddleware::class);

    $app->get('/auth',[AuthPage::class,'get']);
    $app->post('/authData',[AuthPage::class,'authorize']);
    $app->post('/uploadImage',[UploadPage::class,'get']);
    $app->post('/generateMail',[GenerateMailPage::class,'get']);
    $app->get('/templates/views/{id}',[TemplateViewPage::class,'get']);
    $app->post('/createTemplate',[TemplatesPage::class,'createTemplate']);
    $app->post('/updateTemplate',[TemplatesPage::class,'updateTemplate']);
    $app->post('/deleteTemplate',[TemplatesPage::class,'deleteTemplate']);
    $app->post('/clearSession',[ClearSession::class,'get']);
};