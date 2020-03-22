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
        if ($this->isGranted('ROLE_ADMIN')) {
            $projects = $this->projectService->getProjectsOrderedByDate();
            $shortlists = $this->shortListRepository->findAll();

            return $this->render('profile/index.html.twig', [
                'projects' => $projects,
                'shortlists' => $shortlists
            ]);

        } elseif ($this->isGranted('ROLE_USER')) {
            $projects = $user->getProjects();
            $notifications = $this->notificationRepository->findBy(['sender' => $user->getUsername()]);
            if (!empty($notifications)) {
                $this->resolveNotifications($projects, $notifications);
            }

            return $this->render('profile/index.html.twig', [
                'controller_name' => 'ProfileController',
                'projects' => $projects,
            ]);
        } else {
            $shortLists = $user->getShortLists();
            $projects = $this->projectService->getActiveProjectsOrderedByDate();
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
            return $this->render('profile/index.html.twig', [
                'controller_name' => 'ProfileController',
                'shortlists' => $shortLists,
                'projects' => $projects,
                'listed' => $listed
            ]);
        }
    }

    private function resolveNotifications(?Collection $projects, array $notifications): void
    {
        foreach ($projects as $project) {
            /** @var Project $project */
            foreach ($project->getShortLists() as $shortList) {
                foreach ($notifications as $notification) {
                    if ($shortList->getUser()->getId() === $notification->getUser()->getId()) {
                        $shortList->setNotifiedUser(true);
                    }
                }
            }
        }
    }
}
