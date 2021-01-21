<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use App\Form\EditUserFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     *@Route("/profile/userlist" , name="userList")
     */
    public function userList(EntityManagerInterface $em): Response
    {
        //chercher les utilisateurs
        $users = $em->getRepository(User::class)->findAll();
        //afficher dans le twig
        return $this->render('user/user_admin.html.twig', ['users' => $users]);
    }

    /**
     * @Route("/profile/userAdd" , name="userAdd")
     */
    public function addUser(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User;
        $form = $this->createForm(UserFormType::class, $user);

        $form->handleRequest($request);
        // Soumit et valid
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $roles = $form->get('roles')->getData();

            $user->setRoles([0 => $roles]);

            $plainPassword = $form['password']->getdata();

            if (trim($plainPassword) != '') {
                $password = $passwordEncoder->encodePassword($user, $plainPassword);
                $user->setPassWord($password);
            }

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Utilisateur ajouté avec succès');

            return $this->redirectToRoute('userList', []);
        }

        return $this->render('user/user_add.html.twig', ['form' => $form->createView()]);
    }
    /**
     * @Route("/profile/userEdit/{id}" , name="userEdit")
     */
    public function editUser(EntityManagerInterface $em, User $user, Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {

        $form = $this->createForm(EditUserFormType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $form->getData();

            $roles = $form->get('roles')->getData();

            $user->setRoles([0 => $roles]);

            $plainPassword = $form['password']->getData();

            if (trim($plainPassword) != '') {
                //encrypt pass
                $password = $passwordEncoder->encodePassword($user, $plainPassword);
                $user->setPassword($password);
            }
            $em->persist($user);
            $em->flush();


            $this->addFlash('success', 'Utilisateur mis à jour avec succès');

            return $this->redirectToRoute('userList', []);
        }
        return $this->render('user/user_edit.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/profile/delete/{id}",name="userDelete")
     */
    public function deleteUser(User $user, EntityManagerInterface $em)
    {
        $em->remove($user);
        $em->flush();

        $this->addFlash('success', 'Utilisateur effacé avec succès');

        return $this->redirectToRoute('userList', []);
    }
}
