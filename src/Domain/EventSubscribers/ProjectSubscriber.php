<?php
declare(strict_types=1);

namespace App\Domain\EventSubscribers;

use App\Domain\Events\ProjectCreatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ProjectSubscriber implements EventSubscriberInterface
{

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [
            ProjectCreatedEvent::NAME => 'onProjectCreated'
        ];
    }

    public function onProjectCreated(ProjectCreatedEvent $event)
    {
        $project = $event->getProject();

    }
}