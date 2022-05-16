<?php

namespace App\Controller;
use Modele_Afterworks;

use App\Repository\Rolerepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;

class RoleController extends AbstractController
{
    private SerializerInterface $serializer;
    private Rolerepository $rolerepository;

    /**
     * @param SerializerInterface $serializer
     * @param Rolerepository $rolerepository
     *
     */
    public function __construct(SerializerInterface    $serializer,
                                EntityManagerInterface $entityManager,
                                Rolerepository     $rolerepository
    )
    {
        $this->serializer = $serializer;
        $this->entityManager = $entityManager;
        $this->rolerepository = $rolerepository;
    }

    /**
     * @Route("/roles", name="api_roles_all", methods={"GET"})
     */
    public function getroles()
    {
        $roles = $this->rolerepository->findAll();
        $rolesJson = $this->serializer->serialize($roles, 'json');
        return new JsonResponse($rolesJson, Response::HTTP_OK, [], true);
    }
}