<?php
declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Project;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\ORM\Doctrine\Populator;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    /** @var UserPasswordEncoderInterface */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $objectManager)
    {
        $generator = Factory::create();
        $populator = new Populator($generator, $objectManager);

        $populator->addEntity(User::class, 3);
        $populator->addEntity(Project::class, 3);

        $populator->execute();

        $objectManager->flush();
    }
}