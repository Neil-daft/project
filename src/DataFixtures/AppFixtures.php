<?php

namespace App\DataFixtures;

use App\Entity\Project;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
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
            $manager->persist($project);
        }

        $user = new User();
        $user->setUsername('neil');
        $user->setRoles(['ROLE_ADMIN', 'ROLE_USER']);
        $user->setPassword($this->encoder->encodePassword($user, 'password'));
        $user->setEmail('neil@gmail');
        $user->setEmailCanonical('neil@gmails');
        $manager->persist($user);

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
}
