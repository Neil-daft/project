<?php
declare(strict_types=1);

namespace App\Domain\EventSubscribers;

use App\Domain\Events\ProjectCreatedEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ProjectSubscriber implements EventSubscriberInterface
{

    /** @var LoggerInterface */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
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
        $this->logger->log('INFO', 'New Project created', ['date' => $project->getCreatedAt()]);
    }
}