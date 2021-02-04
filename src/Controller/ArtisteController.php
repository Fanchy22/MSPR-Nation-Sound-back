<?php

namespace App\Controller;

use App\Entity\Artiste;
use App\Form\ArtisteType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\VersionStrategy\StaticVersionStrategy;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ArtisteController extends AbstractController
{
    /**
     * @param Request $request
     *
     * @Route("/api/artiste", name="api_artiste_get")
     */
    public function apiArtiste(Request $request, EntityManagerInterface $entityManager)
    {
        $artistes = $entityManager->getRepository(Artiste::class)->findAll();

        $arrayArtistes = $this->artistesToArray($artistes);

        return $this->json($arrayArtistes);
    }

    /**
     * @param $artistes Artiste[]
     */
    private function artistesToArray($artistes)
    {
        $package = new Package(new StaticVersionStrategy('v1'));
        $arrayArtiste = [];
        foreach ($artistes as $artiste) {
            $arrayArtiste[] = [
                'name' => $artiste->getName(),
                'genre' => $artiste->getGenre(),
                'thumbnail' => $package->getUrl($artiste->getThumbnail()),
            ];
        }

        return $arrayArtiste;
    }

    /**
     * @Route("/artiste", name="artiste")
     */
    public function index(Request $request,SluggerInterface $slugger,EntityManagerInterface $entityManager): Response
    {
        $artiste = new Artiste();
        $artisteForm = $this->createForm(ArtisteType::class, $artiste);
        $artisteForm->handleRequest($request);

        if ($artisteForm->isSubmitted() && $artisteForm->isValid()) {
            /** @var UploadedFile $profileFile */
            $profileFile = $artisteForm->get('profile')->getData();
            if ($profileFile) {
                $originalFilename = pathinfo($profileFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$profileFile->guessExtension();
                // Move the file to the directory where profileFile are stored
                try {
                    $profileFile->move(
                        $this->getParameter('profiles_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $artiste->setThumbnail($newFilename);
            }
            //insérer en base de données $artiste
            $entityManager->persist($artiste);
            $entityManager->flush();
        }

        return $this->render('artiste/index.html.twig',
           [
               "artisteForm" => $artisteForm->createView(),
           ]
        );
    }
}
