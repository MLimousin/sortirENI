<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UtilisateurController extends AbstractController
{
    #[Route('/profil', name: 'utilisateur_profil')]
    public function profil(Request $request,
                           EntityManagerInterface $entityManager,
                           UserPasswordEncoderInterface $passwordEncoder
                          ): Response
    {
        $profil = $this->getUser();

        if($request->isMethod('POST')){
            if($request->request->get('pseudo') != ''
                AND $request->request->get('prenom') != ''
                AND $request->request->get('nom') != ''
                AND $request->request->get('telephone') != ''
                AND $request->request->get('email') != ''
                AND $request->request->get('mdp') != ''
                AND $request->request->get('mdpconf') != ''
                AND $request->request->get('campus') != '')
            {
                $rep = $this->getDoctrine()->getRepository(Utilisateur::class);
                $users = $rep->findBy(['pseudo'=>$request->request->get('pseudo')]);
                $i = 0;
                foreach ($users as $user){
                    if($user != $this->getUser()){
                        $i++;
                    }
                }
                if($i==0){
                    if ($request->request->get('mdp') == $request->request->get('mdpconf'))
                    {
                        $profil->setPseudo($request->request->get('pseudo'));
                        $profil->setPrenom($request->request->get('prenom'));
                        $profil->setNom($request->request->get('nom'));
                        $profil->setTelephone($request->request->get('telephone'));
                        $profil->setEmail($request->request->get('email'));
                        $profil->setPassword($passwordEncoder->encodePassword(
                            $profil, $request->request->get('mdp')));
                        if($request->request->get('photo') != null){
                            $profil->setPhoto($request->request->get('photo'));
                        }

                        $repository = $this->getDoctrine()->getRepository(Campus::class);
                        $campus = $repository->findOneBy(['name'=>$request->request->get('campus')]);
                        if($campus){
                           $profil->setCampus($campus);
                        }

                        $entityManager->persist($profil);
                        $entityManager->flush();
                        $this->addFlash('success', 'Votre profil a bien été mis à jour');
                    }else{
                        $this->addFlash('error', 'Les mots de passe sont différents');
                    }
                }else{
                    $this->addFlash('error', 'Ce pseudo existe déjà');
                }

            }else{
               $this->addFlash('error', 'Tous les champs sont obligatoires');
            }
        }
        return $this->render('utilisateur/profil.html.twig', [
            "profil" => $profil,
            'controller_name' => 'UtilisateurController',
        ]);
    }

    #[Route('/profil/utilisateur/detail/{id}', name: 'utilisateur_detail')]
    public function detail($id,
                           UtilisateurRepository $utilisateurRepository
                            ): Response
    {
        $utilisateur = $utilisateurRepository->find($id);
        return $this->render('utilisateur/detail.html.twig', [
            "utilisateur" => $utilisateur,
            'controller_name' => 'UtilisateurController',
        ]);
    }
}
