<?php

namespace App\Controller;

use App\Entity\App;
use App\Form\AppType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    /**
     * @Route("/app", name="app")
     */
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $app = new App();
        $appForm = $this->createForm(AppType::class, $app);
        $appForm->handleRequest($request);

        if ($appForm->isSubmitted() && $appForm->isValid())

            $entityManager->persist($app);
            $entityManager->flush();

        return $this->render('app/index.html.twig', [
            'appForm' => $appForm->createView(),
        ]);
    }
}
