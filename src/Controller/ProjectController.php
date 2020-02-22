<?php
declare(strict_types=1);

namespace App\Controller;

use App\Domain\Status;
use App\Entity\Project;
use App\Form\Project1Type;
use App\Service\ProjectService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @Route("/project")
 */
class ProjectController extends AbstractController
{
    /** @var \App\Service\ProjectService */
    private $projectService;

    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }

    /**
     * @Route("/", name="project_index", methods={"GET"})
     */
    public function index(): Response
    {
        try {
            $this->denyAccessUnlessGranted('ROLE_TRADE');
        } catch (AccessDeniedException $e) {
            return $this->render('error/access_denied.html.twig', [
                'message' => $e->getMessage()
            ]);
        }
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

        return $this->render('project/index.html.twig', [
            'projects' => $projects,
            'listed' => $listed
        ]);
    }

    /**
     * @Route("/new", name="project_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        try {
            $this->denyAccessUnlessGranted('ROLE_USER');
        } catch (AccessDeniedException $e) {
            return $this->render('error/access_denied.html.twig', [
                'message' => $e->getMessage()
            ]);
        }
        $project = new Project();
        $project->setUser($this->getUser());
        $project->setStatus(Status::STATUS_PENDING);
        $project->setCreatedAt(new \DateTime('now'));
        $form = $this->createForm(Project1Type::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->projectService->save($project);

            return $this->redirectToRoute('profile');
        }

        return $this->render('project/new.html.twig', [
            'project' => $project,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="project_show", methods={"GET"})
     */
    public function show(Project $project): Response
    {
        return $this->render('project/show.html.twig', [
            'project' => $project,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="project_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Project $project): Response
    {
        try {
            $this->denyAccessUnlessGranted('ROLE_USER');
        } catch (AccessDeniedException $e) {
            return $this->render('error/access_denied.html.twig', [
                'message' => $e->getMessage()
            ]);
        }

        $form = $this->createForm(Project1Type::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->projectService->update();

            return $this->redirectToRoute('profile');
        }

        return $this->render('project/edit.html.twig', [
            'project' => $project,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="project_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Project $project): Response
    {
        try {
            $this->denyAccessUnlessGranted('ROLE_USER');
        } catch (AccessDeniedException $e) {
            return $this->render('error/access_denied.html.twig', [
                'message' => $e->getMessage()
            ]);
        }

        if ($this->isCsrfTokenValid('delete'.$project->getId(), $request->request->get('_token'))) {
            $this->projectService->delete($project);
        }

        return $this->redirectToRoute('profile');
    }

    /**
     * @Route("/approve/{id}", name="project_approve")
     */
    public function approveProject(Project $project): Response
    {
        $this->projectService->approve($project);

        return $this->redirectToRoute('profile');
    }
}
