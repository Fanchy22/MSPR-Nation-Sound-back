<?php

namespace App\Controller;

use App\Entity\Festival;
use App\Form\FestivalType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FestivalController extends AbstractController
{
    /**
     * @Route("/festival", name="festival")
     */
    public function index(Request $request,EntityManagerInterface $entityManager): Response
    {
        $festival = new Festival();
        $festivalForm = $this->createForm(FestivalType::class, $festival);
        $festivalForm->handleRequest($request);

        if ($festivalForm->isSubmitted() && $festivalForm->isValid())

            //insérer en base de données $artiste
            $entityManager->persist($festival);
            $entityManager->flush();

        return $this->render('festival/index.html.twig',
            [
                "festivalForm" => $festivalForm->createView(),
        ]);
    }
}
