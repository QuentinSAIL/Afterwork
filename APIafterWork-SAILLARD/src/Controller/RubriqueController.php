<?php

namespace App\Controller;
use Modele_Afterworks;

use App\Repository\Rubriquerepository;
use App\Repository\Articlerepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;

class RubriqueController extends AbstractController
{
    private SerializerInterface $serializer;
    private Rubriquerepository $rubriquerepository;
    private Articlerepository $articlerepository;

    /**
     * @param SerializerInterface $serializer
     * @param Rubriquerepository $rubriquerepository
     * @param Articlerepository $articlerepository
     */
    public function __construct(SerializerInterface    $serializer,
                                EntityManagerInterface $entityManager,
                                Rubriquerepository     $rubriquerepository,
                                Articlerepository     $articlerepository
    )
    {
        $this->serializer = $serializer;
        $this->entityManager = $entityManager;
        $this->rubriquerepository = $rubriquerepository;
        $this->articlerepository = $articlerepository;
    }

    /**
     * @Route("/create/rubrique", name="api_créer_rubrique", methods={"POST"})
     */
    public function createRubrique(): Response
    {
        $BDDAfter = new Modele_Afterworks();
        try {
            $contentJson = file_get_contents("php://input");
            $content = json_decode($contentJson, true);
            $BDDAfter->insertRubrique($content);
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

    /**
     * @Route("/rubriques", name="api_rubrique_all", methods={"GET"})
     */
    public function getAllRubrique()
    {
        $rubriques = $this->rubriquerepository->findAll();
        $rubriquesJson = $this->serializer->serialize($rubriques, 'json');
        return new JsonResponse($rubriquesJson, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/rubrique/{idRubrique}", name="détails_rubrique", methods={"GET"})
     */
    public function getOneRubrique(int $idRubrique)
    {
        $rubrique = $this->rubriquerepository->findOneBy(array('idRubrique' => $idRubrique));
        // Tester si la rubrique demandée existe
        if (!$rubrique) {
            // rubrique est null
            // Générer une erreur
            $error = [
                "status" => Response::HTTP_NOT_FOUND,
                "message" => "La rubrique demandée n'existe pas"
            ];
            // Générer une reponse JSON
            return new JsonResponse(json_encode($error), Response::HTTP_NOT_FOUND, [], true);
        }

        $rubriqueJson = $this->serializer->serialize($rubrique, 'json');
        return new JsonResponse($rubriqueJson, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("delete/rubrique/{idRubrique}", name="api_delete_rubrique", methods={"GET"})
     */
    public function deleterubrique(int $idRubrique): Response
    {
        $BDDAfter = new Modele_Afterworks();
        $rubrique = $this->rubriquerepository->find(array('idRubrique' => $idRubrique));
        // Tester si la rubrique demandée existe
        if (!$rubrique) {
            // rubrique est null
            // Générer une erreur
            $error = [
                "status" => Response::HTTP_NOT_FOUND,
                "message" => "La rubrique demandée n'existe pas"
            ];
            // Générer une reponse JSON
            return new JsonResponse(json_encode($error), Response::HTTP_NOT_FOUND, [], true);
        }
        // Avant de supprimer la rubrique, il faut supprimer tous les articles qu'elle possède :
        $articles = $this->articlerepository->findBy(array('idRubrique' => $idRubrique));
        foreach ($articles as $article) {
            $idArticle = $article->getIdArticle();
            $BDDAfter->deleteArticle($idArticle);
        }
        // Maintennant on peut supprimer la rubrique
        $BDDAfter->deleteRubrique($idRubrique);
        return new JsonResponse("true", Response::HTTP_OK, [], true);
    }
}