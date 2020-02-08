<?php

namespace App\Service;

use App\Domain\Status;
use App\Entity\Project;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;

class ProjectService
{
    /** @var ProjectRepository */
    private $projectRepository;

    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(ProjectRepository $projectRepository, EntityManagerInterface $entityManager)
    {
        $this->projectRepository = $projectRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @return Project[]
     */
    public function getProjectsOrderedByDate()
    {
        return $this->projectRepository->findBy([], ['createdAt' => 'desc']);
    }

    public function save(Project $project): void
    {
        $this->entityManager->persist($project);
        $this->update();
    }

    public function delete(Project $project): void
    {
        $this->entityManager->remove($project);
        $this->update();
    }

    public function update(): void
    {
        $this->entityManager->flush();
    }

    public function approve(Project $project)
    {
        $project->setStatus(Status::STATUS_ACTIVE);
        $this->update();
    }

    /**
     * @return Project[]
     */
    public function getActiveProjectsOrderedByDate()
    {
        return $this->projectRepository->findBy(
            ['status' => 'active'], []
        );
    }
}