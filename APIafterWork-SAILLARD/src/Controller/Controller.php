<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Tva;
use App\Repository\Articlerepository;
use App\Repository\Categorierepository;
use App\Repository\Commanderepository;
use App\Repository\Employerepository;
use App\Repository\Clientrepository;
use App\Repository\ProduitCommanderepository;
use App\Repository\Produitrepository;
use App\Repository\Commentairerepository;
use App\Repository\Rolerepository;
use App\Repository\Rubriquerepository;
use App\Repository\Tvarepository;
use Doctrine\ORM\EntityManagerInterface;
use JWT;
use Modele_Afterworks;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/*
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST,GET,PUT,DELETE,OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    die();
}

header("Access-Control-Allow-Origin: *");
header("Content-type: application/json;charset=UTF-8");
*/

class Controller extends AbstractController
{
    private SerializerInterface $serializer;
    private Produitrepository $produitrepository;
    private Commentairerepository $commentairerepository;
    private Categorierepository $categorierepository;
    private Employerepository $employerepository;
    private Clientrepository $clientrepository;
    private Tvarepository $tvarepository;
    private ValidatorInterface $validator;
    private Rubriquerepository $rubriquerepository;
    private Articlerepository $articlerepository;
    private Rolerepository $rolerepository;
    private Commanderepository $commanderepository;
    private ProduitCommanderepository $produitCommanderepository;


    /**
     * @param SerializerInterface $serializer
     * @param Produitrepository $produitrepository
     * @param Commentairerepository $commentairerepository
     * @param Categorierepository $categorierepository
     * @param Employerepository $employerepository
     * @param Clientrepository $clientrepository
     * @param Tvarepository $tvarepository
     * @param Rubriquerepository $rubriquerepository
     * @param Articlerepository $articlerepository
     * @param Rolerepository $rolerepository
     * @param Commanderepository $commanderepository
     * @param ProduitCommanderepository $ProduitCommanderepository
     */
    public function __construct(SerializerInterface       $serializer,
                                Produitrepository         $produitrepository,
                                Commentairerepository     $commentairerepository,
                                Categorierepository       $categorierepository,
                                Employerepository         $employerepository,
                                Clientrepository          $clientrepository,
                                Tvarepository             $tvarepository,
                                EntityManagerInterface    $entityManager,
                                ValidatorInterface        $validator,
                                Rubriquerepository        $rubriquerepository,
                                Articlerepository         $articlerepository,
                                Rolerepository            $rolerepository,
                                Commanderepository        $commanderepository,
                                ProduitCommanderepository $produitCommanderepository
    )
    {
        $this->serializer = $serializer;
        $this->produitrepository = $produitrepository;
        $this->commentairerepository = $commentairerepository;
        $this->categorierepository = $categorierepository;
        $this->employerepository = $employerepository;
        $this->clientrepository = $clientrepository;
        $this->tvarepository = $tvarepository;
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->rubriquerepository = $rubriquerepository;
        $this->articlerepository = $articlerepository;
        $this->rolerepository = $rolerepository;
        $this->commanderepository = $commanderepository;
        $this->produitCommanderepository = $produitCommanderepository;
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
     * @Route("/roles", name="api_roles_all", methods={"GET"})
     */
    public function getroles()
    {
        $categories = $this->rolerepository->findAll();
        $categJson = $this->serializer->serialize($categories, 'json');
        return new JsonResponse($categJson, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/tva", name="api_tva_all", methods={"GET"})
     */
    public function getTva()
    {
        $categories = $this->tvarepository->findAll();
        $categJson = $this->serializer->serialize($categories, 'json');
        return new JsonResponse($categJson, Response::HTTP_OK, [], true);
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
     * @Route("/inscription", name="inscription_client", methods={"POST"})
     */
    public function inscriptionCLient()
    {
        $contentJSON = file_get_contents("php://input");
        $content = json_decode($contentJSON, true);
        $content["password"] = password_hash($content["password"], "argon2i");
        // TEST voir si l'email est déja utilisé :
        if ($this->clientrepository->findOneBy(array("email" => $content["email"]))) {
            //si on arrive la c'est que oui
            http_response_code(401);
            $error = [
                "status" => Response::HTTP_NOT_FOUND,
                "message" => "Email déja utilisé",
            ];
            echo json_encode($error);
            exit();
        }
        $BDDAfter = new Modele_Afterworks();
        $BDDAfter->inscriptionClient($content);
        return new JsonResponse("ok", Response::HTTP_OK, []);
    }

    /**
     * @Route("/inscriptionEmp", name="inscription_employe", methods={"POST"})
     */
    public function inscriptionEmploye()
    {
        $contentJSON = file_get_contents("php://input");
        $content = json_decode($contentJSON, true);
        $content["password"] = password_hash($content["password"], "argon2i");
        // TEST voir si l'email est déja utilisé :
        if ($this->employerepository->findOneBy(array("mail" => $content["email"]))) {
            //si on arrive la c'est que oui
            http_response_code(401);
            $error = [
                "status" => Response::HTTP_NOT_FOUND,
                "message" => "Email déja utilisé",
            ];
            echo json_encode($error);
            exit();
        }
        $BDDAfter = new Modele_Afterworks();
        $BDDAfter->inscriptionEmploye($content);
        return new JsonResponse("ok", Response::HTTP_OK, []);
    }

    /**
     * @Route("/connexion", name="connexion", methods={"POST"})
     */
    public function connexion()
    {
        $SECRET_HMAC = "wzEQYbWXYtvfy6c6tBGzKSVPXoJF0aII";
        $content = file_get_contents("php://input");
        $credentials = json_decode($content, true);
        $client = false;

        $user = $this->employerepository->findOneBy(array("mail" => $credentials["mail"]));

        if (!$user) {
            $user = $this->clientrepository->findOneBy(array("email" => $credentials["mail"]));
            if ($user) {
                // le mail est trouvé dans la table client : ca veut dire que c'est un client qui tente de se connecter et pas un employé
                $client = true;
            }
        }

        if (!$user) {
            // user est null
            // Générer une erreur
            http_response_code(401);
            $error = [
                "status" => Response::HTTP_NOT_FOUND,
                "message" => "Bad credential"
            ];
            // Générer une reponse JSON
            echo json_encode($error);
            exit();

        }
        // Verification du mdp
        if (password_verify($credentials['mot_de_passe'], $user->getPassword()) == false) {
            http_response_code(401);
            $error = [
                "status" => Response::HTTP_NOT_FOUND,
                "message" => "Bad credential",
            ];
            echo json_encode($error);
            exit();
        }

        if ($client == false) {
            $payload = [
                "nom" => $user->getNom(),
                "prenom" => $user->getPrenom(),
                "mail" => $user->getMail(),
                "id_employe" => $user->getIdEmploye(),
                "client" => false
            ];
        } else {
            $payload = [
                "nom" => $user->getNomClient(),
                "prenom" => $user->getPrenomClient(),
                "mail" => $user->getEmailClient(),
                "id_client" => $user->getIdClient(),
                "client" => true
            ];
        }

        $JWT = new JWT();
        $token = $JWT->generate($payload, $SECRET_HMAC, 900);
        http_response_code(200);
        $response = [
            "token" => $token
        ];

        return new JsonResponse($response, Response::HTTP_OK, []);
    }

    /**
     * @Route("/connexionEmp", name="connexionEmp", methods={"POST"})
     */
    public function connexionEmp()
    {
        $SECRET_HMAC = "wzEQYbWXYtvfy6c6tBGzKSVPXoJF0aII";
        $content = file_get_contents("php://input");
        $credentials = json_decode($content, true);

        $user = $this->employerepository->findOneBy(array("mail" => $credentials["mail"]));

        if (!$user || $user->getIdRole()->getIdRole() == 3 || $user->getIdRole()->getIdRole() == 4) {
            // user est null ou est un client ou est un redac
            // Générer une erreur
            http_response_code(401);
            $error = [
                "status" => Response::HTTP_NOT_FOUND,
                "message" => "Bad credential"
            ];
            // Générer une reponse JSON
            echo json_encode($error);
            exit();

        }

        // Verification du mdp
        if (password_verify($credentials['mot_de_passe'], $user->getPassword()) == false) {
            http_response_code(401);
            $error = [
                "status" => Response::HTTP_NOT_FOUND,
                "message" => "Bad credential",
            ];
            echo json_encode($error);
            exit();
        }
        $payload = [
            "nom" => $user->getNom(),
            "prenom" => $user->getPrenom(),
            "mail" => $user->getMail(),
            "id_employe" => $user->getIdEmploye()
        ];

        $JWT = new JWT();
        $token = $JWT->generate($payload, $SECRET_HMAC, 900);
        http_response_code(200);
        $response = [
            "token" => $token
        ];

        return new JsonResponse($response, Response::HTTP_OK, []);
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

    /**
     * @Route("/employe/{idUser}", name="get_info_employe", methods={"GET"})
     */
    public function getInfoEmploye(int $idUser)
    {
        $user = $this->employerepository->findBy(array('idEmploye' => $idUser));
        // Tester si l'employé demandé existe
        if (!$user) {
            // employé est null
            // Générer une erreur
            $error = [
                "status" => Response::HTTP_NOT_FOUND,
                "message" => "L'employé demandé n'existe pas"
                // normalement c'est impossible car il est déja connecté mais bon au cas ou ¯\_(ツ)_/¯
            ];
            // Générer une reponse JSON
            return new JsonResponse(json_encode($error), Response::HTTP_NOT_FOUND, [], true);
        }
        $userJson = $this->serializer->serialize($user, 'json');
        return new JsonResponse($userJson, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/client/{idUser}", name="get_info_client", methods={"GET"})
     */
    public function getInfoClient(int $idUser)
    {
        $user = $this->clientrepository->findBy(array('idClient' => $idUser));
        // Tester si le client demandé existe
        if (!$user) {
            // client est null
            // Générer une erreur
            $error = [
                "status" => Response::HTTP_NOT_FOUND,
                "message" => "L'employé demandé n'existe pas"
                // normalement c'est impossible car il est déja connecté mais bon au cas ou ¯\_(ツ)_/¯
            ];
            // Générer une reponse JSON
            return new JsonResponse(json_encode($error), Response::HTTP_NOT_FOUND, [], true);
        }
        $userJson = $this->serializer->serialize($user, 'json');
        return new JsonResponse($userJson, Response::HTTP_OK, [], true);
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
     * @Route("/rubriques", name="api_rubrique_all", methods={"GET"})
     */
    public function getAllRubrique()
    {
        $produits = $this->rubriquerepository->findAll();
        $produitsJson = $produitsJson = $this->serializer->serialize($produits, 'json');
        return new JsonResponse($produitsJson, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/reset/mdp/{idEmploye}", name="api_reset_mdp", methods={"GET"})
     */
    public function ResetMdpUser(int $idEmploye)
    {
        $BDDAfter = new Modele_Afterworks();
        $BDDAfter->resetMDP($idEmploye);
        return new JsonResponse("ok", Response::HTTP_OK, [], true);
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

    /**
     * @Route("/clients", name="api_clients_all", methods={"GET"})
     */
    public function getAllClients()
    {
        $clients = $this->clientrepository->findAll();
        $clientsJson = $this->serializer->serialize($clients, 'json');
        return new JsonResponse($clientsJson, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/employes", name="api_employes_all", methods={"GET"})
     */
    public function getAllEmployes()
    {
        $employes = $this->employerepository->findAll();
        $employesJson = $this->serializer->serialize($employes, 'json');
        return new JsonResponse($employesJson, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/abonnementNewsletter/{idClient}", name="update_newsletter_client", methods={"POST"})
     */
    public function updateNewsLetter(int $idClient)
    {
        $client = $this->clientrepository->findBy(array('idClient' => $idClient));
        // Tester si le produit demandé existe
        if (!$client) {
            // produit est null
            // Générer une erreur
            $error = [
                "status" => Response::HTTP_NOT_FOUND,
                "message" => "Le client demandé n'existe pas"
                // normalement c'est impossible car il est déja connecté mais bon au cas ou ¯\_(ツ)_/¯
            ];
            // Générer une reponse JSON
            return new JsonResponse(json_encode($error), Response::HTTP_NOT_FOUND, [], true);
        }
        $BDDAfter = new Modele_Afterworks();
        if ($client[0]->getAbonnementNewsletter() == 0) {
            $value = 1;
        } else {
            $value = 0;
        }
        $BDDAfter->updateNewsLetter($value, $idClient);

        return new JsonResponse("true", Response::HTTP_OK, [], true);
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
