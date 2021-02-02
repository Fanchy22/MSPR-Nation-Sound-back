<?php

namespace App\Controller;

use App\Entity\Restauration;
use App\Form\RestaurationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RestaurationController extends AbstractController
{
    /**
     * @Route("/restauration", name="restauration")
     */
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $restauration = new Restauration();
        $restaurationForm = $this->createForm(RestaurationType::class, $restauration);
        $restaurationForm->handleRequest($request);

        if ($restaurationForm->isSubmitted() && $restaurationForm->isValid())

            $entityManager->persist($restauration);
        $entityManager->flush();

        return $this->render('restauration/index.html.twig', [
            'restaurationForm' => $restaurationForm->createView(),
        ]);
    }
}