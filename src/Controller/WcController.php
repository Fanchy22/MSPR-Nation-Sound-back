<?php

namespace App\Controller;

use App\Entity\WC;
use App\Form\WcType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WcController extends AbstractController
{
    /**
     * @Route("/wc", name="wc")
     */
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $wc = new WC();
        $wcForm = $this->createForm(WCType::class, $wc);
        $wcForm->handleRequest($request);

        if ($wcForm->isSubmitted() && $wcForm->isValid())

            $entityManager->persist($wc);
        $entityManager->flush();

        return $this->render('wc/index.html.twig', [
            'wcForm' => $wcForm->createView(),
        ]);
    }
}