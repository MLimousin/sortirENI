<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Utilisateur;
use App\Entity\Ville;
use App\Form\CreationSortieType;
use App\Repository\UtilisateurRepository;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function PHPUnit\Framework\returnArgument;

class SortiesController extends AbstractController
{

    #[Route('/create_sortie', name: 'sortie_create')]

    public function create(Request $request,
                           UtilisateurRepository $utilisateurRepository,
                            EntityManagerInterface $entityManager

    ): Response {
        $sortie = new Sortie();
        $user = $this->getUser();
        $repository = $this->getDoctrine()->getRepository(Utilisateur::class);
        $utilisateurs = $repository->findAll();
        foreach($utilisateurs as $utilisateur){
            if($utilisateur==$user){
                $sortie->setCampus($user->getCampus());
            }
        }
        $repositoryVille = $this->getDoctrine()->getRepository(Ville::class);
        $repositoryLieu = $this->getDoctrine()->getRepository(Lieu::class);
        $villes = $repositoryVille->findAll();
        $lieux = $repositoryLieu->findAll();

        $creationSortieForm = $this->createForm(CreationSortieType::class, $sortie);

        $creationSortieForm -> handleRequest($request);
        if ($creationSortieForm->isSubmitted()){
            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash('success', 'Sortie ajoutée');

        }

        return $this->render('creation/create.html.twig', [
            'creationSortieForm' => $creationSortieForm->createView()
        ]);
    }

}