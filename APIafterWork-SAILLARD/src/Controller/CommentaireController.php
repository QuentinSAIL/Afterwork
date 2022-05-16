<?php

namespace App\Controller;
use App\Repository\Commentairerepository;
use Modele_Afterworks;

use App\Repository\Produitrepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;

class CommentaireController extends AbstractController
{
    private SerializerInterface $serializer;
    private Produitrepository $produitrepository;
    private Commentairerepository $commentairerepository;


    /**
     * @param SerializerInterface $serializer
     * @param Produitrepository $produitrepository
     * @param Commentairerepository $commentairerepository
     */
    public function __construct(SerializerInterface    $serializer,
                                EntityManagerInterface $entityManager,
                                Produitrepository      $produitrepository,
                                Commentairerepository  $commentairerepository
    )
    {
        $this->serializer = $serializer;
        $this->entityManager = $entityManager;
        $this->commentairerepository = $commentairerepository;
        $this->produitrepository = $produitrepository;
    }

    /**
     * @Route("/commentaires/{idProduit}", name="commentaire_par_id_produit", methods={"GET"})
     */
    public function getCommentaireByIdProduit(int $idProduit)
    {
        $produit = $this->produitrepository->findBy(array('idProduit' => $idProduit));
        // Tester si le produit demandé existe
        if (!$produit) {
            // produit est null
            // Générer une erreur
            $error = [
                "status" => Response::HTTP_NOT_FOUND,
                "message" => "Le produit demandé n'existe pas"
            ];
            // Générer une reponse JSON
            return new JsonResponse(json_encode($error), Response::HTTP_NOT_FOUND, [], true);
        }
        //$commentaire = $this->commentairerepository->findBy(array('idProduit' => $idProduit));
        $commentaireJson = $this->serializer->serialize($this->commentairerepository->findBy(array('idProduit' => $idProduit)), 'json');
        //dd(is_scalar($commentaireJson));
        return new JsonResponse($commentaireJson, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/create/comment/{idProduct}", name="api_ajouter_commentaire", methods={"POST"})
     */
    public function createComment(int $idProduct): Response
    {
        $BDDAfter = new Modele_Afterworks();
        $user = $this->getUser();
        try {
            $contentJson = file_get_contents("php://input");
            $content = json_decode($contentJson, true);
            $content["id_produit"] = $idProduct;
            //dd($content);
            $BDDAfter->insertComment($content);
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