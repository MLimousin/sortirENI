<?php

namespace App\Controller;

use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UtilisateurController extends AbstractController
{
    #[Route('/profil', name: 'utilisateur_profil')]
    public function profil(): Response
    {
        $profil = $this->getUser();
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
