<?php

namespace App\Controller;

use App\Entity\Ville;
use App\Form\VilleFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin",name="admin_")
 */
class VilleController extends AbstractController
{
    /**
     * liste ville d'un département
     * @Route("/ville/detail/{id}", name="detailVille")
     */
    public function detailVille(Ville $ville): Response
    {
        return $this->render('ville/detailVille.html.twig', [
            'ville' => $ville,
        ]);
    }


    /**
     * @Route("/ville/add",name="ajoutVille")
     */
    public function addVille(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {

        $ville = new Ville;
        $form = $this->createForm(VilleFormType::class, $ville);

        $form->handleRequest($request);

        // Soumit et valid
        if ($form->isSubmitted() && $form->isValid()) {
            $ville->setSlug($slugger->slug($ville->getNom()));

            $idDepartement = $form['departement']->getData()->getId();


            $em->persist($ville);
            $em->flush();

            $this->addFlash('success', 'Ville ajouté avec succès');

            return $this->redirectToRoute('admin_departementVille', ['id' => $idDepartement]);
        }

        return $this->render('ville/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/ville/edit/{id}", name="editVille")
     */
    public function editVille(Request $request, EntityManagerInterface $em, $id): Response
    {


        $ville = $em->getRepository(Ville::class)->find($id);
        $form = $this->createForm(VilleFormType::class, $ville);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $idDepartement = $ville->getDepartement()->getId();
            $em->persist($ville);
            $em->flush();

            $this->addFlash('success', 'Ville édité avec succès');

            return $this->redirectToRoute('admin_departementVille', ['id' => $idDepartement]);
        }

        return $this->render('ville/edit.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/ville/delete/{id}", name= "deleteVille")
     */
    public function deleteVille(Ville $ville, EntityManagerInterface $em): Response
    {

        $idDepartement = $ville->getDepartement()->getId();

        $em->remove($ville);
        $em->flush();

        $this->addFlash('success', 'Ville effacé avec succès');

        return $this->redirectToRoute('admin_departementVille', ['id' => $idDepartement]);
    }
}
