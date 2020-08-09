<?php
declare(strict_types=1);

namespace App\Controller;

use App\Service\ProjectService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class  HomePageController extends AbstractController
{
    /**
     * @var ProjectService
     */
    private $projectService;

    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }
    /**
     * @Route("/", name="home_page")
     */
    public function index()
    {
        $activeProjects = $this->projectService->getActiveProjectsOrderedByDate(3);

        return $this->render('home_page/index.html.twig', [
            'controller_name' => 'HomePageController',
            'activeProjects' => $activeProjects
        ]);
    }
}
