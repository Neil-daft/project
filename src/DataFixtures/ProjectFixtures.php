<?php

namespace App\DataFixtures;

use App\Entity\Project;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ProjectFixtures extends Fixture implements DependentFixtureInterface
{
    /** @var \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 20; $i++) {
            $project = new Project();
            $project->setOwner('owner_' . $i);
            $project->setFirstCreated(date_create($this->getRandomDate($i)));
            $project->setDescription('SomeLorem ipsum text goes here');
            $project->setValue((int)$i . 00);
            $project->setTitle('The Title');
            $project->setUser($this->getReference(User::class.'_1'));
            $manager->persist($project);
        }

        $manager->flush();
    }

    /**
     * @param int $i
     * @return string
     */
    private function getRandomDate($i)
    {
        $string = '2019-01-'.$i;

        return $string;
    }

    /**
     * @inheritDoc
     */
    public function getDependencies()
    {
        return [UserFixtures::class];
    }
}
