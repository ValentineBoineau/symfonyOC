<?php

namespace OC\PlatformBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use OC\PlatformBundle\Entity\Category;

class LoadCategory implements FixtureInterface{
    public function load(ObjectManager $manager){
        $names=array('Dev web', 'Dev mobile','Graphisme','Integration','Reseau');
        foreach ($names as $name){
            $category=new Category();
            $category->setName($name);
            $manager->persist($category);
        }
        $manager->flush();
    }
}