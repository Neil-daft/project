<?php
declare(strict_types=1);

namespace App\Controller;

use App\Domain\Events\ShortListEvent;
use App\Domain\EventSubscribers\ShortListEventSubscriber;
use App\Entity\Project;
use App\Entity\ShortList;
use App\Service\ShortListService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * @Route("/shortlist")
 */
class ShortListController extends AbstractController
{
    /** @var \Symfony\Component\Routing\Generator\UrlGeneratorInterface */
    private $urlGenerator;

    /** @var \App\Service\ShortListService */
    private $shortListService;

    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    /**  @var ShortListEventSubscriber */
    private $shortListEventSubscriber;

    public function __construct(
        UrlGeneratorInterface $urlGenerator,
        ShortListService $shortListService,
        EventDispatcherInterface $eventDispatcher,
        ShortListEventSubscriber $shortListEventSubscriber
    ) {
        $this->urlGenerator = $urlGenerator;
        $this->shortListService = $shortListService;
        $this->eventDispatcher = $eventDispatcher;
        $this->shortListEventSubscriber = $shortListEventSubscriber;
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
        $shortList = $this->shortListService->acceptShortList($shortList);
        $this->eventDispatcher->addSubscriber($this->shortListEventSubscriber);
        $event = new ShortListEvent($shortList);
        $this->eventDispatcher->dispatch($event, ShortListEvent::NAME);

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
