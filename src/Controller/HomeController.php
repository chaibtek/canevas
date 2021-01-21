<?php

namespace App\Controller;

use App\Entity\Ville;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(EntityManagerInterface $em): Response
    {

        $listeVilles = $em->getRepository(Ville::class)->findAll();

        return $this->render('home/index.html.twig', ['listeVilles' => $listeVilles]);
    }

    /**
     * Détails de la ville pour le rôle User
     * @Route("/ville/{id}-{slug}", name="vueVille")
     */
    public function detailVille(Ville $ville): Response
    {
        return $this->render('ville/detailVilleUser.html.twig', [
            'ville' => $ville,
        ]);
    }
}
