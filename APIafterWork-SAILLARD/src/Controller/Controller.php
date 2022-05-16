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
}
