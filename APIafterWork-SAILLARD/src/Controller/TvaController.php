<?php

namespace App\Controller;
use Modele_Afterworks;

use App\Repository\Tvarepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;

class TvaController extends AbstractController
{
    private SerializerInterface $serializer;
    private Tvarepository $tvarepository;

    /**
     * @param SerializerInterface $serializer
     * @param Tvarepository $tvarepository
     */
    public function __construct(SerializerInterface    $serializer,
                                EntityManagerInterface $entityManager,
                                Tvarepository          $tvarepository
    )
    {
        $this->serializer = $serializer;
        $this->entityManager = $entityManager;
        $this->tvarepository = $tvarepository;
    }

    /**
     * @Route("/tva", name="api_tva_all", methods={"GET"})
     */
    public function getTva()
    {
        $tva = $this->tvarepository->findAll();
        $tvaJson = $this->serializer->serialize($tva, 'json');
        return new JsonResponse($tvaJson, Response::HTTP_OK, [], true);
    }

}