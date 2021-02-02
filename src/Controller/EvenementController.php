<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Form\EvenementType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class EvenementController extends AbstractController
{
    /**
     * @Route("/evenement", name="evenement")
     */
    public function index(Request $request, SluggerInterface $slugger, EntityManagerInterface $entityManager): Response
    {
        $evenement = new Evenement();
        $evenementForm = $this->createForm(EvenementType::class, $evenement);
        $evenementForm->handleRequest($request);

        if ($evenementForm->isSubmitted() && $evenementForm->isValid()) {
            /** @var UploadedFile $thumbnailFile */
            $thumbnailFile = $evenementForm->get('thumbnail')->getData();
            if ($thumbnailFile) {
                $originalFilename = pathinfo($thumbnailFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $thumbnailFile->guessExtension();
                // Move the file to the directory where profileFile are stored
                try {
                    $thumbnailFile->move(
                        $this->getParameter('thumbnail_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $evenement->setThumbnail($newFilename);
            }
            //insérer en base de données $evenement
            $entityManager->persist($evenement);
            $entityManager->flush();
        }

        return $this->render('evenement/index.html.twig',
            [
                "evenementForm" => $evenementForm->createView(),
            ]
        );
    }
}

