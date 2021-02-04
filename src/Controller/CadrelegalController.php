<?php

namespace App\Controller;

use App\Entity\CadreLegal;
use App\Form\CadreLegalType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CadrelegalController extends AbstractController
{
    /**
     * @Route("/cadrelegal", name="cadrelegal")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $cadrelegal = new CadreLegal();
        $cadrelegalForm = $this->createForm(CadreLegalType::class, $cadrelegal);
        $cadrelegalForm->handleRequest($request);

        if ($cadrelegalForm->isSubmitted() && $cadrelegalForm->isValid())

            $entityManager->persist($cadrelegal);
            $entityManager->flush();

        return $this->render('cadrelegal/index.html.twig', [
            'cadrelegalForm' => $cadrelegalForm->createView(),
        ]);
    }
}
