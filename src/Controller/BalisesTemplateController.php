<?php

namespace App\Controller;

use App\Entity\Artiste;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BalisesTemplateController extends AbstractController
{
    /**
     * @Route("/balises/template", name="balises_template")
     */
    public function index(): Response
    {
        return $this->render('balises_template/index.html.twig', [
            'controller_name' => 'BalisesTemplateController',
        ]);
    }

    /**
     * la route peut ressembler à /artist?genre=rock
     *
     *
     * @Route("/artist", name="get_artist", methods={"GET"})
     */
    public function artist(EntityManagerInterface $entityManager, Request $request)
    {
        // récupération en GET des données de filtre
        $name = $request->get('name');
        $genre = $request->get('genre');

        // récupération des données qui vont bien avec l'entity manager
        //// find / findBy / écriture requete dans repository
        // -> obtention d'une liste d'objet Artist

        // transformer cette liste d'Artiste en array
        //// vu dans les TD Rest

        $arrayArtists = [
            [
                'name' => 'Carlos',
                'genre' => 'P',
                'thumbnail' => '/photo/belle.png',
            ],
        ];

        return $this->json($arrayArtists);
    }

    /**
     * pour appel API
     *
     *
     * @Route("/artist/{id}", name="save_artist", methods={"POST, PUT, PATCH"})
     */
    public function saveArtist($id = null, EntityManagerInterface $entityManager, Request $request)
    {
        if ($id) {
            // avec find, je récup l'artist qui correspond à id
        } else {
            $artist = new Artiste();
        }

        $name = $request->get('name');
        $genre = $request->get('genre');
        // ...
        $artist->setName($name);
        $artist->setGenre($genre);

        $entityManager->persist($artist);
        $entityManager->flush();

        // transforme artist en array
        $arrayArtist = [
            // ...
        ];

        return $this->json($arrayArtist, $id ? Response::HTTP_OK: Response::HTTP_CREATED);
    }


    // UPSERT d'artiste depuis un twig
    // Voir cours sur les formulaires




}
