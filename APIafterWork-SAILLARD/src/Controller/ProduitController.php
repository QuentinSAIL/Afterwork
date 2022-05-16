<?php

namespace App\Controller;
use App\Entity\Employe;
use Modele_Afterworks;
use JWT;

use App\Repository\Produitrepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;

class ProduitController extends AbstractController
{
    private SerializerInterface $serializer;
    private Produitrepository $produitrepository;

    /**
     * @param SerializerInterface $serializer
     * @param Produitrepository $produitrepository
     */
    public function __construct(SerializerInterface    $serializer,
                                EntityManagerInterface $entityManager,
                                Produitrepository       $produitrepository
    )
    {
        $this->serializer = $serializer;
        $this->entityManager = $entityManager;
        $this->produitrepository = $produitrepository;
    }

    /**
     * @Route("/produits", name="api_produit_all", methods={"GET"})
     */
    public function getAllProduit()
    {
        $produits = $this->produitrepository->findAll();
        $produitsJson = $this->serializer->serialize($produits, 'json');
        return new JsonResponse($produitsJson, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/produits/details/{idProduit}", name="id_produit", methods={"GET"})
     */
    public function getDetailsProduit(int $idProduit)
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
        $produitJson = $this->serializer->serialize($produit, 'json');
        return new JsonResponse($produitJson, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/create/product", name="api_ajouter_produit", methods={"POST"})
     */
    public function createProduit(): Response
    {
        $BDDAfter = new Modele_Afterworks();
        $user = $this->getUser();
        try {
            $contentJson = file_get_contents("php://input");
            $content = json_decode($contentJson, true);
            $BDDAfter->insertProduct($content);
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
     * @Route("/modifier/produit/{idProduit}", name="api_modifier_produit", methods={"POST"})
     */
    public function modifierProduit(int $idProduit)
    {
        $BDDAfter = new Modele_Afterworks();
        try {
            $contentJson = file_get_contents("php://input");
            $content = json_decode($contentJson, true);
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
            $BDDAfter->updateProduct($content, $idProduit);
            return new JsonResponse('mis à jour', Response::HTTP_OK, [], true);
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