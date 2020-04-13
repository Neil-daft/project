<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Project;
use App\Entity\User;
use App\Repository\ShortListRepository;
use App\Service\ProjectService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /** @var ShortListRepository */
    private $shortListRepository;

    /** @var ProjectService */
    private $projectService;

    public function __construct(ProjectService $projectService, ShortListRepository $shortListRepository)
    {
        $this->shortListRepository = $shortListRepository;
        $this->projectService = $projectService;
    }

    /**
     * @Route("/profile", name="profile")
     */
    public function index()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        /** @var User $user */
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

                return $this->render('profile/index.html.twig', [
                    'controller_name' => 'ProfileController',
                    'projects' => $projects
                ]);
                break;
            default:
                $shortLists = $user->getShortLists();
                $projects = $this->projectService->getActiveProjectsOrderedByDate();

                $listed = $this->resolveTradeUserShortLists($projects);

                return $this->render('profile/index.html.twig', [
                    'shortlists' => $shortLists,
                    'projects' => $projects,
                    'listed' => $listed,
                    'active' => true
                ]);
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
