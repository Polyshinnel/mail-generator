<?php
declare(strict_types=1);

use \App\Migration\Migration;
use Illuminate\Database\Schema\Blueprint;


final class Templates extends Migration
{
    public function up(){
        $this->schema->create('templates',function (Blueprint $table){
            $table->increments('id');
            $table->string('name','127');
            $table->string('img','256');
            $table->date('date_create');
            $table->json('json');
            $table->longText('html');
        });
    }
    public function down()
    {
        $this->schema->drop('templates');
    }

}
