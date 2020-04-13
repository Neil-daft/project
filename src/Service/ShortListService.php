<?php
declare(strict_types=1);

namespace App\Service;

use App\Domain\Status;
use App\Entity\Project;
use App\Entity\ShortList;
use App\Entity\User;
use App\Repository\ShortListRepository;
use Doctrine\ORM\EntityManagerInterface;

class ShortListService
{
    /** @var \Doctrine\ORM\EntityManagerInterface */
    private $entityManager;

    /** @var \App\Repository\ShortListRepository */
    private $shortListRepository;

    public function __construct(EntityManagerInterface $entityManager, ShortListRepository $shortListRepository)
    {
        $this->entityManager = $entityManager;
        $this->shortListRepository = $shortListRepository;
    }

    public function createNewShortList(Project $project, User $user): void
    {
        $shortList = new ShortList();
        $shortList->setUser($user);
        $shortList->setCreatedAt(new \DateTime('now'));
        $shortList->setProject($project);
        $shortList->setStatus(Status::STATUS_SUBMITTED);

        $this->entityManager->persist($shortList);
        $this->save();
    }

    public function acceptShortList(ShortList $shortList): ShortList
    {
        $shortList->setStatus(Status::STATUS_ACCEPTED);

        $this->save();

        return $shortList;
    }

    private function save(): void
    {
        $this->entityManager->flush();
    }
}
