<?php

namespace App\Controller;
use Modele_Afterworks;

use App\Repository\Categorierepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;

class CategorieController extends AbstractController
{
    private SerializerInterface $serializer;
    private Categorierepository $categorierepository;


    /**
     * @param SerializerInterface $serializer
     * @param Categorierepository $categorierepository
     *
     */
    public function __construct(SerializerInterface    $serializer,
                                EntityManagerInterface $entityManager,
                                Categorierepository    $categorierepository
    )
    {
        $this->serializer = $serializer;
        $this->entityManager = $entityManager;
        $this->categorierepository = $categorierepository;
    }

    /**
     * @Route("/categorie", name="api_categorie_all", methods={"GET"})
     */
    public function getCateg()
    {
        $categories = $this->categorierepository->findAll();
        $categJson = $this->serializer->serialize($categories, 'json');
        return new JsonResponse($categJson, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/create/category", name="api_ajouter_categorie", methods={"POST"})
     */
    public function createCategorie(): Response
    {
        $BDDAfter = new Modele_Afterworks();
        $user = $this->getUser();
        try {
            $contentJson = file_get_contents("php://input");
            $content = json_decode($contentJson, true);
            $BDDAfter->insertCategory($content);
            return new JsonResponse($contentJson, Response::HTTP_CREATED, [], true);
        } // Intercepter une éventuelle exception
        catch (NotEncodableValueException $exception) {
            $error = [
                "status" => Response::HTTP_BAD_REQUEST,
                "message" => "Le JSON envoyé dans la requête n'est pas valide"
            ];
            // Générer une reponse JSON
            return new JsonResponse(json_encode($error), Response::HTTP_BAD_REQUEST, [], true);
        }
    }
}


