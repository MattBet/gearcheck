<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture {

    public function load(ObjectManager $manager)
    {
        // TODO: Implement load() method.

        $arr = array(['Powerleveling, Mythic+, Raid']);

        $category1 = new Category();
        $category2 = new Category();
        $category3 = new Category();

        $category1->setImage('cc3080e478e047a8305872bd09809faa.jpeg');
        $category2->setImage('a9a478838db872ce60bc84d0c61a6e4d.jpeg');
        $category3->setImage('4c51484733bf615d43f273cff8d8efaf.jpeg');

        $category1->setName('Powerleveling');
        $category2->setName('Gold making');
        $category3->setName('Mythic+');

        $manager->persist($category1);
        $manager->persist($category2);
        $manager->persist($category3);

        $arr_img = ['d0651063ea5bcafd2e97997e71c82552.jpeg','33ba09b6027c7273e79a157c727cc537.jpeg','0770eb485a5d2d0e23474734d6bef157.jpeg', '4c51484733bf615d43f273cff8d8efaf.jpeg'];
        for ($i = 0; $i< 30; $i++)
        {
            shuffle($arr_img);
            $product = new Product();
            $product->setCategory($category1);
            $product->setName("Test_".$i);
            $product->setLongDesc("Monstrat olim claritudinis quam duae pristinae monstrat admodum aegre quidem Seleuci et quam aegre Claudius interneciva coloniam Caesar exornant ut.");
            $product->setPrice(15);
            $product->setShortDesc("Simulationem publici placuerat codicem nimis.");
            $product->setEta("");
            $product->setImage($arr_img[rand(0,count($arr_img)-1)]);

            $manager->persist($product);

        }

        $manager->flush();
    }
}