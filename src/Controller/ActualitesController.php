<?php

namespace App\Controller;

use App\Entity\Actualites;
use App\Form\ActualitesType;
use Symfony\Component\Asset\Package;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Asset\VersionStrategy\StaticVersionStrategy;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ActualitesController extends AbstractController
{
    /**
     * @param Request $request
     *
     * @Route("/api/actualites", name="api_actualite_get")
     */
    public function apiActualites(Request $request, EntityManagerInterface $entityManager)
    {
        $actualites = $entityManager->getRepository(Actualites::class)->findAll();

        $arrayActualites = $this->actualitesToArray($actualites);

        return $this->json($arrayActualites, Response::HTTP_OK, ['Access-Control-Allow-Origin' => '*']);

    }

    /**
     * @param $actualites Actualites[]
     */
    private function actualitesToArray($actualites)
    {
        $package = new Package(new StaticVersionStrategy('v1'));
        $arrayActualite = [];
        foreach ($actualites as $actualite) {
            $arrayActualite[] = [
                'title' => $actualite->getTitle(),
                'content' => $actualite->getContent(),
                'thumbnail' => $actualite->getThumbnail(),
                'emergency' => $actualite->getEmergency(),
            ];
        }

        return $arrayActualite;
    }
    /**
     * @Route("/actualites", name="actualites")
     */
    public function index(Request $request, SluggerInterface $slugger, EntityManagerInterface $entityManager): Response
    {
        $actualites = new Actualites();
        $actualitesForm = $this->createForm(ActualitesType::class, $actualites);
        $actualitesForm->handleRequest($request);

        if ($actualitesForm->isSubmitted() && $actualitesForm->isValid()) {

            //insérer en base de données $actualites
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
