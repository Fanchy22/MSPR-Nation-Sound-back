<?php

namespace App\Controller;

use App\Entity\Partenaire;
use App\Form\PartenaireType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class PartenaireController extends AbstractController
{
    /**
     * @Route("/partenaire", name="partenaire")
     */
    public function index(Request $request,SluggerInterface $slugger,EntityManagerInterface $entityManager): Response
    {
        $partenaire = new Partenaire();
        $partenaireForm = $this->createForm(PartenaireType::class, $partenaire);
        $partenaireForm->handleRequest($request);

        if ($partenaireForm->isSubmitted() && $partenaireForm->isValid()) {
            /** @var UploadedFile $thumbnailFile */
            $thumbnailFile = $partenaireForm->get('thumbnail')->getData();
            if ($thumbnailFile) {
                $originalFilename = pathinfo($thumbnailFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$thumbnailFile->guessExtension();
                // Move the file to the directory where profileFile are stored
                try {
                    $thumbnailFile->move(
                        $this->getParameter('thumbnail_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $partenaire->setThumbnail($newFilename);
            }
            //insérer en base de données $artiste
            $entityManager->persist($partenaire);
            $entityManager->flush();
        }

        return $this->render('partenaire/index.html.twig',
            [
                "partenaireForm" => $partenaireForm->createView(),
            ]
        );
    }
}
