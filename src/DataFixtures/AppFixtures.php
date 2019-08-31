<?php

namespace App\DataFixtures;

use App\Entity\Project;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 20; $i++) {
            $project = new Project();
            $project->setOwner('owner_' . $i);
            $project->setFirstCreated(date_create($this->getRandomDate($i)));
            $project->setDescription('SomeLorem ipsum text goes here');
            $project->setValue((int)$i . 00);
            $project->setTitle('The Title');
            $manager->persist($project);
        }

        $manager->flush();
    }

    private function getRandomDate($i)
    {
        $string = '2019-01-'.$i;

        return $string;

    }
}
