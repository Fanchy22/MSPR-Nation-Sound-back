<?php

namespace App\Controller;

use App\Entity\Images;
use App\Form\ImagesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ImagesController extends AbstractController
{
    /**
     * @Route("/images", name="images")
     */
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $images = new Images();
        $imagesForm = $this->createForm(imagesType::class, $images);
        $imagesForm->handleRequest($request);

        if ($imagesForm->isSubmitted() && $imagesForm->isValid())

            $entityManager->persist($images);
            $entityManager->flush();

        return $this->render('images/index.html.twig', [
            'imagesForm' => $imagesForm->createView(),
        ]);
    }
}
