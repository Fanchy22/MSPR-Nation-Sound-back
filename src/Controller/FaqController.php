<?php

namespace App\Controller;

use App\Entity\FAQ;
use App\Form\FaqType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FaqController extends AbstractController
{
    /**
     * @Route("/faq", name="faq")
     */
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $faq = new FAQ();
        $faqForm = $this->createForm(FaqType::class, $faq);
        $faqForm->handleRequest($request);

        if ($faqForm->isSubmitted() && $faqForm->isValid())

            $entityManager->persist($faq);
        $entityManager->flush();

        return $this->render('faq/index.html.twig', [
            'faqForm' => $faqForm->createView(),
        ]);
    }
}
