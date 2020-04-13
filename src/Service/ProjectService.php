<?php
declare(strict_types=1);

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

    /**
     * @return Project[]
     */
    public function getActiveProjectsOrderedByDate(int $limit = null)
    {
        return $this->projectRepository->findBy(['status' => 'active'], ['createdAt' => 'desc'], $limit);
    }

    public function approve(Project $project): void
    {
        $project->setStatus(Status::STATUS_ACTIVE);
        $this->update();
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

    public function closeProject(Project $project): void
    {
        $project->setStatus(Status::STATUS_CLOSED);
        $this->update();;
    }

    public function update(): void
    {
        $this->entityManager->flush();
    }
}