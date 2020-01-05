<?php

namespace App\Doctrine;

use App\Entity\User;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class HashPasswordListener implements EventSubscriber
{

    /** @var UserPasswordEncoderInterface  */
    private $userPasswordEncoder;

    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
    }
    /**
     * @inheritDoc
     */
    public function getSubscribedEvents()
    {
        return ['prePersist', 'preUpdate'];
    }

    public function prePersist(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getEntity();
        if (!$entity instanceof User) {
            return;
        }
        $this->encodePassword($entity);
    }

    public function preUpdate(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getEntity();
        if (!$entity instanceof User) {
            return;
        }
        $this->encodePassword($entity);
        // necessary to force the update to see the change
        $em = $eventArgs->getEntityManager();
        $meta = $em->getClassMetadata(get_class($entity));
        $em->getUnitOfWork()->recomputeSingleEntityChangeSet($meta, $entity);
    }

    /**
     * @param User $entity
     */
    private function encodePassword($entity): void
    {
        $encoded = $this->userPasswordEncoder->encodePassword(
            $entity,
            $entity->getPlainPassword()
        );
        $entity->setPassword($encoded);
    }
}