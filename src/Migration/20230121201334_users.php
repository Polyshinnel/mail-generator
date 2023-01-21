<?php
declare(strict_types=1);

use \App\Migration\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

final class Users extends Migration
{
    public function up(){
        $this->schema->create('users',function (Blueprint $table){
            $table->increments('id');
            $table->string('name','127');
            $table->string('password','127');
        });
        $users = [
            [
                'name' => 'admin',
                'password' => md5('b3SojCWl')
            ],
            [
                'name' => 'andrey',
                'password' => md5('test123')
            ]
        ];
        foreach ($users as $user) {
            Capsule::table('users')->insert($user);
        }

    }
    public function down()
    {
        $this->schema->drop('users');
    }
}
