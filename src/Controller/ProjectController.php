<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\User;
use App\Repository\ProjectRepository as ProjectRepositoryAlias;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProjectController extends AbstractController
{
    /**
     * @Route("/project/{id}", name="project_show_by_id", requirements={"id"="\d+"})
     * @param \App\Entity\Project $project
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Project $project)
    {
        return $this->render(
            'project/projects.html.twig',
            ['project' => $project]
        );
    }

    /**
     * @Route("/projects", name="get_all_projects")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getAllProjects()
    {
        $entityManager = $this->getDoctrine()->getManager();
        /** @var ProjectRepositoryAlias $repository */
        $repository = $entityManager->getRepository(Project::class);

        $projects = $repository->findAll();

        return $this->render(
            'project/all_projects.html.twig',
            ['projects' => $projects]
        );
    }

    /**
     * @Route("/admin/projects/{username}", name="user_projects")
     * @ParamConverter("user", class="App\Entity\User")
     * @param \App\Entity\User $user
     */
    public function getUserProjects(User $user): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $repository = $entityManager->getRepository(Project::class);

        $projects = $repository->findBy(['owner' => $user->getUsername()]);

        return $this->render(
            'project/projects.html.twig',
            [
                'user' => $user,
                'projects' => $projects
            ]
        );
    }

    /**
     * @Route("/admin/project/edit/{id}", name="project_edit")
     * @ParamConverter("user", class="App\Entity\User")
     * @param \App\Entity\User $user
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editProject(User $user, int $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $repository = $entityManager->getRepository(Project::class);
        $project = $repository->find($id);
        $project->setTitle('New product name!');
        $entityManager->flush();

        return $this->redirectToRoute('user_projects', ['username' => $user->getUsername()]);
    }
}
