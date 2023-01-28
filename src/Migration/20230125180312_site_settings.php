<?php
declare(strict_types=1);

use \App\Migration\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Capsule\Manager as Capsule;

final class SiteSettings extends Migration
{
    public function up(){
        $this->schema->create('site_settings',function (Blueprint $table){
            $table->increments('id');
            $table->string('name','127');
            $table->string('site_addr','256');
            $table->string('site_addr_short','256');
            $table->string('site_xml','256');
            $table->string('logo','256');
            $table->string('delivery','256');
            $table->string('discount','256');
            $table->string('feed_user','256');
            $table->string('feed_pass','256');
        });

        $siteSettingsArr = [
            [
                'name' => 'Joseph Kitchen',
                'site_addr' => 'https://josephkitchen.ru/',
                'site_addr_short' => 'josephkitchen.ru',
                'site_xml' => 'https://josephkitchen.ru/fullcatalog.xml',
                'logo' => 'images/logos/joseph-kitchen.png',
                'delivery' => 'https://josephkitchen.ru/dostavka-i-oplata',
                'discount' => 'https://josephkitchen.ru/discount',
                'feed_user' => 'kidsberry',
                'feed_pass' => 'klVm8(0KL%'
            ],
            [
                'name' => 'Umbra Shop',
                'site_addr' => 'https://umbrashop.ru/',
                'site_addr_short' => 'umbrashop.ru',
                'site_xml' => 'https://umbrashop.ru/fullcatalog.xml',
                'logo' => 'images/logos/umbra-shop.png',
                'delivery' => 'https://umbrashop.ru/dostavka-i-oplata',
                'discount' => 'https://umbrashop.ru/discount',
                'feed_user' => 'kidsberry',
                'feed_pass' => 'klVm8(0KL%'
            ],
            [
                'name' => 'Mason Cash',
                'site_addr' => 'https://masoncash.me/',
                'site_addr_short' => 'masoncash.me',
                'site_xml' => 'https://masoncash.me/fullcatalog.xml',
                'logo' => 'images/logos/mason-cash.png',
                'delivery' => 'https://masoncash.me/dostavka-i-oplata',
                'discount' => 'https://masoncash.me/discounts',
                'feed_user' => 'kidsberry',
                'feed_pass' => 'klVm8(0KL%'
            ],
            [
                'name' => 'Reisenthel',
                'site_addr' => 'https://reisenthelshop.ru/',
                'site_addr_short' => 'reisenthelshop.ru',
                'site_xml' => 'https://reisenthelshop.ru/fullcatalog.xml',
                'logo' => 'images/logos/reisenthel.png',
                'delivery' => 'https://reisenthelshop.ru/dostavka-i-oplata',
                'discount' => 'https://reisenthelshop.ru/discount',
                'feed_user' => 'kidsberry',
                'feed_pass' => 'klVm8(0KL%'
            ],
            [
                'name' => 'Monbento',
                'site_addr' => 'https://monbento.me/',
                'site_addr_short' => 'monbento.me',
                'site_xml' => 'https://monbento.me/fullcatalog.xml',
                'logo' => 'images/logos/monbento.png',
                'delivery' => 'https://monbento.me/dostavka-i-oplata',
                'discount' => 'https://monbento.me/discounts',
                'feed_user' => 'kidsberry',
                'feed_pass' => 'klVm8(0KL%'
            ],
            [
                'name' => 'Guzzini',
                'site_addr' => 'https://guzzini.me/',
                'site_addr_short' => 'guzzini.me',
                'site_xml' => 'https://guzzini.me/fullcatalog.xml',
                'logo' => 'images/logos/guzzini.png',
                'delivery' => 'https://guzzini.me/dostavka-i-oplata',
                'discount' => 'https://guzzini.me/discounts',
                'feed_user' => 'kidsberry',
                'feed_pass' => 'klVm8(0KL%'
            ],
            [
                'name' => 'Liberty Jones',
                'site_addr' => 'https://liberty-jones.ru/',
                'site_addr_short' => 'liberty-jones.ru',
                'site_xml' => 'https://liberty-jones.ru/fullcatalog.xml',
                'logo' => 'images/logos/libertyjones.png',
                'delivery' => 'https://liberty-jones.ru/dostavka-i-oplata',
                'discount' => 'https://liberty-jones.ru/discounts',
                'feed_user' => 'kidsberry',
                'feed_pass' => 'klVm8(0KL%'
            ],
            [
                'name' => 'Smart Solutions',
                'site_addr' => 'https://smart-solution.me/',
                'site_addr_short' => 'smart-solution.me',
                'site_xml' => 'https://smart-solution.me/fullcatalog.xml',
                'logo' => 'images/logos/SmartSolutions.png',
                'delivery' => 'https://smart-solution.me/dostavka-i-oplata',
                'discount' => 'https://smart-solution.me/discounts',
                'feed_user' => 'kidsberry',
                'feed_pass' => 'klVm8(0KL%'
            ],
            [
                'name' => 'Bergenson Bjorn',
                'site_addr' => 'https://bergensons.ru/',
                'site_addr_short' => 'bergensons.ru',
                'site_xml' => 'https://bergensons.ru/fullcatalog.xml',
                'logo' => 'images/logos/bergensons.png',
                'delivery' => 'https://bergensons.ru/dostavka-i-oplata',
                'discount' => 'https://bergensons.ru/discounts',
                'feed_user' => 'kidsberry',
                'feed_pass' => 'klVm8(0KL%'
            ],
            [
                'name' => 'Silikomart',
                'site_addr' => 'https://silikoshop.ru/',
                'site_addr_short' => 'silikoshop.ru',
                'site_xml' => 'https://silikoshop.ru/fullcatalog.xml',
                'logo' => 'images/logos/silikoshop.png',
                'delivery' => 'https://silikoshop.ru/dostavka-i-oplata',
                'discount' => 'https://silikoshop.ru/discounts',
                'feed_user' => 'kidsberry',
                'feed_pass' => 'klVm8(0KL%'
            ],
            [
                'name' => 'Wildtoys',
                'site_addr' => 'https://wildtoys.ru/',
                'site_addr_short' => 'wildtoys.ru',
                'site_xml' => 'https://wildtoys.ru/fullcatalog.xml',
                'logo' => 'images/logos/wildtoys.png',
                'delivery' => 'https://wildtoys.ru/dostavka-i-oplata',
                'discount' => 'https://wildtoys.ru/discounts',
                'feed_user' => 'kidsberry',
                'feed_pass' => 'klVm8(0KL%'
            ],
            [
                'name' => 'SCHLEICH',
                'site_addr' => 'https://schleichtoys.ru/',
                'site_addr_short' => 'schleichtoys.ru',
                'site_xml' => 'https://schleichtoys.ru/fullcatalog.xml',
                'logo' => 'images/logos/schleich.png',
                'delivery' => 'https://schleichtoys.ru/dostavka-i-oplata',
                'discount' => 'https://schleichtoys.ru/discounts',
                'feed_user' => 'kidsberry',
                'feed_pass' => 'klVm8(0KL%'
            ],
            [
                'name' => 'Djeco',
                'site_addr' => 'https://djecoshop.ru/',
                'site_addr_short' => 'djecoshop.ru',
                'site_xml' => 'https://djecoshop.ru/fullcatalog.xml',
                'logo' => 'images/logos/djeco.png',
                'delivery' => 'https://djecoshop.ru/dostavka-i-oplata',
                'discount' => 'https://djecoshop.ru/discounts',
                'feed_user' => 'kidsberry',
                'feed_pass' => 'klVm8(0KL%'
            ],
            [
                'name' => 'SafariToys',
                'site_addr' => 'https://safaritoys.ru/',
                'site_addr_short' => 'safaritoys.ru',
                'site_xml' => 'https://safaritoys.ru/fullcatalog.xml',
                'logo' => 'images/logos/safaritoys.png',
                'delivery' => 'https://safaritoys.ru/dostavka-i-oplata',
                'discount' => 'https://safaritoys.ru/discounts',
                'feed_user' => 'kidsberry',
                'feed_pass' => 'klVm8(0KL%'
            ],
            [
                'name' => 'Typhoon',
                'site_addr' => 'https://typhoonstore.ru/',
                'site_addr_short' => 'typhoonstore.ru',
                'site_xml' => 'https://typhoonstore.ru/fullcatalog.xml',
                'logo' => 'images/logos/typhoonstore.png',
                'delivery' => 'https://typhoonstore.ru/dostavka-i-oplata',
                'discount' => 'https://typhoonstore.ru/discount',
                'feed_user' => 'kidsberry',
                'feed_pass' => 'klVm8(0KL%'
            ],
        ];

        foreach ($siteSettingsArr as $item) {
            Capsule::table('site_settings')->insert($item);
        }

    }
    public function down()
    {
        $this->schema->drop('site_settings');
    }
}
