<?php

namespace App\Controller;
use App\Entity\Employe;
use Modele_Afterworks;
use JWT;

use App\Repository\Clientrepository;
use App\Repository\Employerepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;

class ClientController extends AbstractController
{
    private SerializerInterface $serializer;
    private Clientrepository $clientrepository;
    private Employerepository $employerepository;


    /**
     * @param SerializerInterface $serializer
     * @param Clientrepository $clientrepository
     * @param Employerepository $employerepository
     *
     */
    public function __construct(SerializerInterface    $serializer,
                                EntityManagerInterface $entityManager,
                                Clientrepository       $clientrepository,
                                Employerepository      $employerepository
    )
    {
        $this->serializer = $serializer;
        $this->entityManager = $entityManager;
        $this->clientrepository = $clientrepository;
        $this->employerepository = $employerepository;
    }

    /**
     * @Route("/abonnementNewsletter/{idClient}", name="update_newsletter_client", methods={"POST"})
     */
    public function updateNewsLetter(int $idClient)
    {
        $client = $this->clientrepository->findBy(array('idClient' => $idClient));
        // Tester si le client demandé existe
        if (!$client) {
            // client est null
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
     * @Route("/clients", name="api_clients_all", methods={"GET"})
     */
    public function getAllClients()
    {
        $clients = $this->clientrepository->findAll();
        $clientsJson = $this->serializer->serialize($clients, 'json');
        return new JsonResponse($clientsJson, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/client/{idUser}", name="get_info_client", methods={"GET"})
     */
    public function getInfoClient(int $idUser)
    {
        $user = $this->clientrepository->findBy(array('idClient' => $idUser));
        // Tester si le user demandé existe
        if (!$user) {
            // user est null
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
}