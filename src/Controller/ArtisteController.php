<?php

namespace App\Controller;

use App\Entity\Artiste;
use App\Form\ArtisteType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArtisteController extends AbstractController
{
    /**
     * @Route("/artiste", name="artiste")
     */
    public function index(): Response
    {
        $artiste = new Artiste();
        $artisteForm = $this->createForm(ArtisteType::class, $artiste);

        return $this->render('artiste/index.html.twig',
           [
               "artisteForm" => $artisteForm->createView(),
           ]
        );
    }
}
