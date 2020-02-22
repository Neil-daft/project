<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\ShortList;
use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @Route("/shortlist")
 */
class ShortListController extends AbstractController
{
    /** @var \App\Repository\ProjectRepository */
    private $projectRepository;

    /** @var \Symfony\Component\Routing\Generator\UrlGeneratorInterface */
    private $urlGenerator;

    public function __construct(ProjectRepository $projectRepository, UrlGeneratorInterface $urlGenerator)
    {
        $this->projectRepository = $projectRepository;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @Route("/", name="short_list")
     */
    public function index()
    {
        return $this->render('short_list/index.html.twig', [
            'controller_name' => 'ShortListController',
        ]);
    }

    /**
     * @Route("/new/{id}", name="shortlist_create")
     */
    public function create(int $id)
    {
        $project = $this->projectRepository->find($id);
        $shortList = new ShortList();
        $shortList->setUser($this->getUser());
        $shortList->setCreatedAt(new \DateTime('now'));
        $shortList->setProject($project);

        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->persist($shortList);
        $entityManager->flush();

        return new RedirectResponse($this->urlGenerator->generate('profile'));
    }

}
