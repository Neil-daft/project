<?php
declare(strict_types=1);

namespace App\Domain\Events;

use App\Entity\Project;

class ProjectCreatedEvent
{
    const NAME = 'project.create';

    /**
     * @var \App\Entity\Project
     */
    private $project;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    public function getProject(): Project
    {
        return $this->project;
    }
}
