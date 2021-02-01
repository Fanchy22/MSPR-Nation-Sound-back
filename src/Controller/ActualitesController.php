<?php

namespace App\Controller;

use App\Entity\Actualites;
use App\Form\ActualitesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ActualitesController extends AbstractController
{
    /**
     * @Route("/actualites", name="actualites")
     */
    public function index(Request $request, SluggerInterface $slugger, EntityManagerInterface $entityManager): Response
    {
        $actualites = new Actualites();
        $actualitesForm = $this->createForm(ActualitesType::class, $actualites);
        $actualitesForm->handleRequest($request);

        if ($actualitesForm->isSubmitted() && $actualitesForm->isValid()) {
            /** @var UploadedFile $thumbnailFile */
            $thumbnailFile = $actualitesForm->get('thumbnail')->getData();
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
                $actualites->setThumbnail($newFilename);
            }
            //insérer en base de données $artiste
            $entityManager->persist($actualites);
            $entityManager->flush();
        }

        return $this->render('actualites/index.html.twig',
            [
                "actualitesForm" => $actualitesForm->createView(),
            ]
        );
    }
}
