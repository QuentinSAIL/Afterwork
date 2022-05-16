<?php

namespace App\Controller;
use Modele_Afterworks;

use App\Repository\Commanderepository;
use App\Repository\Produitrepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;

class CommandeController extends AbstractController
{
    private SerializerInterface $serializer;
    private Commanderepository $commanderepository;
    private Produitrepository $produitrepository;


    /**
     * @param SerializerInterface $serializer
     * @param Commanderepository $commanderepository
     * @param Produitrepository $produitrepository
     *
     */
    public function __construct(SerializerInterface    $serializer,
                                EntityManagerInterface $entityManager,
                                Commanderepository    $commanderepository,
                                Produitrepository     $produitrepository
    )
    {
        $this->serializer = $serializer;
        $this->entityManager = $entityManager;
        $this->commanderepository = $commanderepository;
        $this->produitrepository = $produitrepository;
    }

    /**
     * @Route("/create/commande", name="api_create_commande", methods={"POST"})
     */
    public function createCommande()
    {
        $contentJSON = file_get_contents("php://input");
        $content = json_decode($contentJSON, true);

        $BDDAfter = new Modele_Afterworks();
        $BDDAfter->insertCommand($content);
        $idCommand = $BDDAfter->getLastCommand();
        foreach ($content["quantiteProd"] as $idProd => $quantite ) {
            $produit = $this->produitrepository->find(array('idProduit' => $idProd));
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
            $BDDAfter->insertProductInCommand($idCommand["id_commande"], $idProd, $quantite, $produit->getPrixUnitaireHt(), 0.2);
        }
        return new JsonResponse("ok", Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/commandes", name="api_commands_all", methods={"GET"})
     */
    public function getAllCommands()
    {
        $commandes = $this->commanderepository->findAll();
        $commandesJson = $this->serializer->serialize($commandes, 'json');
        return new JsonResponse($commandesJson, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/commandes/details/{idCommand}", name="api_command_detail", methods={"GET"})
     */
    public function getDetailsCommand(int $idCommand)
    {
        $commandes = $this->commanderepository->findBy(array('idCommande' => $idCommand));
        if (!$commandes) {
            $error = [
                "status" => Response::HTTP_NOT_FOUND,
                "message" => "La commande demandée n'existe pas"
            ];
            // Générer une reponse JSON
            return new JsonResponse(json_encode($error), Response::HTTP_NOT_FOUND, [], true);
        }
        $commandesJson = $this->serializer->serialize($commandes, 'json');
        return new JsonResponse($commandesJson, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/commandes/products/{idCommand}", name="api_commandProducts_detail", methods={"GET"})
     */
    public function getDetailsCommandProducts($idCommand)
    {
        $BDDAfter = new Modele_Afterworks();
        $commandeProducts = $BDDAfter->getCommandProducts($idCommand);
        if (!$commandeProducts) {
            $error = [
                "status" => Response::HTTP_NOT_FOUND,
                "message" => "La commande demandée n'existe pas"
            ];
            // Générer une reponse JSON
            return new JsonResponse(json_encode($error), Response::HTTP_NOT_FOUND, [], true);
        }
        $commandeProductsJson = $this->serializer->serialize($commandeProducts, 'json');

        return new JsonResponse($commandeProductsJson, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/CommandePlus/{idCommand}/{idEmploye}", name="api_commande_plus", methods={"GET"})
     */
    public function plusOneStautCommand(int $idCommand, int $idEmploye)
    {
        $BDDAfter = new Modele_Afterworks();
        $command = $BDDAfter->CommandPlusOneIdStatut($idCommand, $idEmploye);
        return new JsonResponse("ok", Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/CommandSetStatut/{idCommand}/{idStatut}/{idEmploye}", name="api_commande_set", methods={"GET"})
     */
    public function setStatutCommand(int $idCommand,int $idStatut, int $idEmploye)
    {
        $BDDAfter = new Modele_Afterworks();
        $command = $BDDAfter->CommandSetStatut($idCommand, $idStatut, $idEmploye);
        return new JsonResponse("ok", Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/deleteCommand/{idCommand}", name="api_commande_delete", methods={"GET"})
     */
    public function DeleteCommand(int $idCommand)
    {
        $BDDAfter = new Modele_Afterworks();
        $command = $BDDAfter->CommandDelete($idCommand);
        return new JsonResponse("ok", Response::HTTP_OK, [], true);
    }
}


