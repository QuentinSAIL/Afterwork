<?php

namespace App\Controller;
use Modele_Afterworks;

use App\Repository\Articlerepository;
use App\Repository\Rubriquerepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;

class ArticleController extends AbstractController
{
    private SerializerInterface $serializer;
    private Articlerepository $articlerepository;
    private Rubriquerepository $rubriquerepository;


    /**
     * @param SerializerInterface $serializer
     * @param Articlerepository $articlerepository
     * @param Rubriquerepository $rubriquerepository
     *
     */
    public function __construct(SerializerInterface    $serializer,
                                EntityManagerInterface $entityManager,
                                Articlerepository      $articlerepository,
                                Rubriquerepository     $rubriquerepository
    )
    {
        $this->serializer = $serializer;
        $this->entityManager = $entityManager;
        $this->articlerepository = $articlerepository;
        $this->rubriquerepository = $rubriquerepository;
    }

    /**
     * @Route("/create/article", name="api_créer_article", methods={"POST"})
     */
    public function createArticle(): Response
    {
        $BDDAfter = new Modele_Afterworks();
        try {
            $contentJson = file_get_contents("php://input");
            $content = json_decode($contentJson, true);
            $BDDAfter->insertArticle($content);
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
     * @Route("/articles/{idRubrique}", name="articles_par_idRubrique", methods={"GET"})
     */
    public function getArticlesByIdrubrique(int $idRubrique)
    {
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

        $articlesJson = $this->serializer->serialize($this->articlerepository->findBy(array('idRubrique' => $idRubrique)), 'json');
        return new JsonResponse($articlesJson, Response::HTTP_OK, [], true);
    }


    /**
     * @Route("delete/article/{idArticle}", name="api_delete_article", methods={"GET"})
     */
    public function deleteArticle(int $idArticle): Response
    {
        $BDDAfter = new Modele_Afterworks();
        $article = $this->articlerepository->find(array('idArticle' => $idArticle));
        // Tester si la rubrique demandée existe
        if (!$article) {
            // article est null
            // Générer une erreur
            $error = [
                "status" => Response::HTTP_NOT_FOUND,
                "message" => "L'article demandé n'existe pas"
            ];
            // Générer une reponse JSON
            return new JsonResponse(json_encode($error), Response::HTTP_NOT_FOUND, [], true);
        }
        $BDDAfter->deleteArticle($idArticle);
        return new JsonResponse("true", Response::HTTP_OK, [], true);
    }
}