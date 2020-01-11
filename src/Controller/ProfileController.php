<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use App\Repository\ShortListRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @var \App\Repository\ProjectRepository
     */
    private $projectRepository;

    /**
     * @var \App\Repository\ShortListRepository
     */
    private $shortListRepository;

    public function __construct(ProjectRepository $projectRepository, ShortListRepository $shortListRepository)
    {
        $this->projectRepository = $projectRepository;
        $this->shortListRepository = $shortListRepository;
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
            $projects = $this->projectRepository->findAll();
            $shortlists = $this->shortListRepository->findAll();

            return $this->render('profile/index.html.twig', [
                'projects' => $projects,
                'shortlists' => $shortlists
            ]);

        } elseif ($this->isGranted('ROLE_USER')) {
            $projects = $user->getProjects();

            return $this->render('profile/index.html.twig', [
                'controller_name' => 'ProfileController',
                'projects' => $projects
            ]);
        } else {
            $shortLists = $user->getShortLists();

            return $this->render('profile/index.html.twig', [
                'controller_name' => 'ProfileController',
                'shortlists' => $shortLists
            ]);
        }
    }
}
