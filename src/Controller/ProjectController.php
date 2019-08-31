<?php

namespace App\Controller;

use App\Entity\Project;
use App\Repository\ProjectRepository as ProjectRepositoryAlias;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
            'project/project.html.twig',
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
}
