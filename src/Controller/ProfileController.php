<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Project;
use App\Repository\NotificationRepository;
use App\Repository\ShortListRepository;
use App\Service\ProjectService;
use Doctrine\Common\Collections\Collection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @var \App\Repository\ShortListRepository
     */
    private $shortListRepository;

    /**
     * @var \App\Service\ProjectService
     */
    private $projectService;

    /**
     * @var \App\Repository\NotificationRepository
     */
    private $notificationRepository;

    public function __construct(ProjectService $projectService, ShortListRepository $shortListRepository, NotificationRepository $notificationRepository)
    {
        $this->shortListRepository = $shortListRepository;
        $this->projectService = $projectService;
        $this->notificationRepository = $notificationRepository;
    }
    /**
     * @Route("/profile", name="profile")
     */
    public function index()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        switch ($this) {
            case $this->isGranted('ROLE_ADMIN'):
                $projects = $this->projectService->getProjectsOrderedByDate();
                $shortlists = $this->shortListRepository->findAll();

                return $this->render('profile/index.html.twig', [
                    'projects' => $projects,
                    'shortlists' => $shortlists
                ]);
                break;
            case $this->isGranted('ROLE_USER'):
                $projects = $user->getProjects();
                $notifications = $this->notificationRepository->findBy(['sender' => $user->getUsername()]);
                if (!empty($notifications)) {
                    $this->resolveNotifications($projects, $notifications);
                }

                return $this->render('profile/index.html.twig', [
                    'controller_name' => 'ProfileController',
                    'projects' => $projects
                ]);
                break;
            default:
                $shortLists = $user->getShortLists();
                $projects = $this->projectService->getActiveProjectsOrderedByDate();
                $notifications = $this->notificationRepository->findBy(['user' => $this->getUser()]);
                foreach ($projects as $project) {
                    foreach ($notifications as $notification) {
                        if ($notification->getProject()->getId() == $project->getId()) {
                            $project->addNotification($notification);
                        }
                    }
                }

                $listed = $this->resolveTradeUserShortLists($projects);

                return $this->render('profile/index.html.twig', [
                    'shortlists' => $shortLists,
                    'projects' => $projects,
                    'listed' => $listed,
                    'notifications' => $notifications,

                ]);
        }
    }

    private function resolveNotifications(?Collection $projects, array $notifications): void
    {
        foreach ($projects as $project) {
            foreach ($project->getShortLists() as $shortList) {
                foreach ($notifications as $notification) {
                    if ($notification->getProject()->getId() == $project->getId()) {
                        if ($notification->getUser() == $shortList->getUser()) {
                            /** @var \App\Entity\ShortList $shortList */
                            $shortList->setNotifiedUser(true);
                        }
                    }
                }
            }
        }
    }

    private function resolveTradeUserShortLists(array $projects): array
    {
        $listed = [];
        foreach ($projects as $project) {
            /** @var Project $project */
            if (!empty($project->getShortLists())) {
                foreach ($project->getShortLists() as $shortList) {
                    if ($this->getUser() == $shortList->getUser()) {
                        $listed[$project->getId()] = true;
                    }
                }
            }
        }

        return $listed;
    }
}
