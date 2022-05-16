<?php

namespace App\Controller;
use App\Entity\Employe;
use Modele_Afterworks;
use JWT;

use App\Repository\Employerepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;

class EmployeController extends AbstractController
{
    private SerializerInterface $serializer;
    private Employerepository $employerepository;


    /**
     * @param SerializerInterface $serializer
     * @param Employerepository $employerepository
     *
     */
    public function __construct(SerializerInterface    $serializer,
                                EntityManagerInterface $entityManager,
                                Employerepository      $employerepository
    )
    {
        $this->serializer = $serializer;
        $this->entityManager = $entityManager;
        $this->employerepository = $employerepository;
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
     * @Route("/employes", name="api_employes_all", methods={"GET"})
     */
    public function getAllEmployes()
    {
        $employes = $this->employerepository->findAll();
        $employesJson = $this->serializer->serialize($employes, 'json');
        return new JsonResponse($employesJson, Response::HTTP_OK, [], true);
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
}