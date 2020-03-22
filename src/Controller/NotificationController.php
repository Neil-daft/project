<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Notification;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class NotificationController extends AbstractController
{
    /** @var UrlGeneratorInterface */
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }
    /**
     * @Route("/notify/{id}", name="new_notification")
     */
    public function new(User $user)
    {
        $notification = new Notification();
        $notification->setUser($user);
        $notification->setSender($this->getUser()->getUserName());
        $notification->setCreatedAt(new \DateTime('now'));

        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->persist($notification);
        $entityManager->flush();

        $this->addFlash(
            'notice',
            'Notified the user'
        );

        return new RedirectResponse($this->urlGenerator->generate('profile'));
    }
}