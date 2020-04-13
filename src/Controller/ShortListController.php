<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Project;
use App\Entity\ShortList;
use App\Service\ShortListService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @Route("/shortlist")
 */
class ShortListController extends AbstractController
{
    /** @var \Symfony\Component\Routing\Generator\UrlGeneratorInterface */
    private $urlGenerator;

    /** @var \App\Service\ShortListService */
    private $shortListService;

    public function __construct(UrlGeneratorInterface $urlGenerator, ShortListService $shortListService)
    {
        $this->urlGenerator = $urlGenerator;
        $this->shortListService = $shortListService;
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
     * @Route("/new/{projectId}", name="shortlist_create")
     * @ParamConverter("project", options={"id"="projectId"})
     */
    public function create(Project $project): RedirectResponse
    {
        $this->shortListService->createNewShortList($project, $this->getUser());

        return $this->redirectToProfile();
    }

    /**
     * @Route("/update/{id}", name="shortlist_update")
     * @ParamConverter("shortlist", options={"id"="id"})
     */
    public function updateStatus(ShortList $shortList)
    {
        $this->shortListService->updateShortListStatus($shortList);

        $this->addFlash(
            'notice',
            'Notified the user'
        );

        return $this->redirectToProfile();
    }

    private function redirectToProfile(): RedirectResponse
    {
        return new RedirectResponse($this->urlGenerator->generate('profile'));
    }
}
