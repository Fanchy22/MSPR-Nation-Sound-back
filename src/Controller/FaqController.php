<?php

namespace App\Controller;

use App\Entity\FAQ;
use App\Form\FaqType;
use Symfony\Component\Asset\Package;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Asset\VersionStrategy\StaticVersionStrategy;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FaqController extends AbstractController
{
    /**
     * @param Request $request
     *
     * @Route("/api/faq", name="api_faq_get")
     */
    public function apiFaq(Request $request, EntityManagerInterface $entityManager)
    {
        $faqs = $entityManager->getRepository(FAQ::class)->findAll();

        $arrayFaqs = $this->faqsToArray($faqs);

        return $this->json($arrayFaqs, Response::HTTP_OK, ['Access-Control-Allow-Origin' => '*']);

    }

    /**
     * @param $faqs FAQ[]
     */
    private function faqsToArray($faqs)
    {
        $package = new Package(new StaticVersionStrategy('v1'));
        $arrayFaq = [];
        foreach ($faqs as $faq) {
            $arrayFaq[] = [
                'question' => $faq->getQuestion(),
                'response' => $faq->getReponse(),
                'visible' => $faq->getVisible(),
            ];
        }

        return $arrayFaq;
    }
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
