<?php

namespace App\Controller;

use App\Entity\Informations;
use App\Form\InformationsType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InformationsController extends AbstractController
{
    /**
     * @Route("/informations", name="informations")
     */
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $informations = new Informations();
        $informationsForm = $this->createForm(InformationsType::class, $informations);
        $informationsForm->handleRequest($request);

        if ($informationsForm->isSubmitted() && $informationsForm->isValid())

            $entityManager->persist($informations);
            $entityManager->flush();

        return $this->render('informations/index.html.twig', [
            'informationsForm' => $informationsForm->createView(),
        ]);
    }
}
