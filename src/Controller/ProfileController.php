<?php

namespace App\Controller;

use App\Entity\Project;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="profile")
     */
    public function index()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        $projects = $user->getProjects();

        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
            'projects' => $projects
        ]);
    }
}
