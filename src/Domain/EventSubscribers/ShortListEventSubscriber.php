<?php
declare(strict_types=1);

namespace App\Domain\EventSubscribers;

use App\Domain\Events\ShortListEvent;
use App\Entity\Charge;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ShortListEventSubscriber implements EventSubscriberInterface
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [
            ShortListEvent::NAME => 'onShortListAccepted'
        ];
    }

    public function onShortListAccepted(ShortListEvent $event): void
    {
        $shortList = $event->getShortList();
        $charge = new Charge();
        $charge->setAmount(5.50);
        $charge->setCreatedAt(new \DateTime('now'));
        $charge->setShortList($shortList);

        $this->entityManager->persist($charge);
        $this->entityManager->flush();
        $event->stopPropagation();
    }
}