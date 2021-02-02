<?php

namespace App\Controller;

use App\Entity\Emplacement;
use App\Form\EmplacementType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class EmplacementController extends AbstractController
{
    /**
     * @Route("/emplacement", name="emplacement")
     */
    public function index(Request $request, SluggerInterface $slugger, EntityManagerInterface $entityManager): Response
    {
        $emplacement = new Emplacement();
        $emplacementForm = $this->createForm(EmplacementType::class, $emplacement);
        $emplacementForm->handleRequest($request);

        if ($emplacementForm->isSubmitted() && $emplacementForm->isValid()) {
            /** @var UploadedFile $thumbnailFile */
            $thumbnailFile = $emplacementForm->get('thumbnail')->getData();
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
                $emplacement->setThumbnail($newFilename);
            }
            //insérer en base de données $emplacement
            $entityManager->persist($emplacement);
            $entityManager->flush();
        }

        return $this->render('emplacement/index.html.twig',
            [
                "emplacementForm" => $emplacementForm->createView(),
            ]
        );
    }
}

