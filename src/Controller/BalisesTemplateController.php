<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BalisesTemplateController extends AbstractController
{
    /**
     * @Route("/balises/template", name="balises_template")
     */
    public function index(): Response
    {
        return $this->render('balises_template/index.html.twig', [
            'controller_name' => 'BalisesTemplateController',
        ]);
    }
}
