<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Utilisateur;
use App\Entity\Ville;
use App\Form\CreationSortieType;
use App\Repository\CampusRepository;
use App\Repository\LieuRepository;
use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
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
    #[Route('/sorties/liste', name:'sortie_liste')]
        public function liste(SortieRepository $sortieRepository): Response
    {
        /*
        $sortiesFiltrees = new SortieFiltre();
        $form = $this->createForm(SortieFiltreFormType::class, $sortiesFiltrees);
        $form->handleRequest($request);
        $listeSortiesFiltrees = $sortieRepository->findSearch($sortiesFiltrees);
        return $this->render('sorties/liste.html.twig', [
            'sorties' => $listeSortiesFiltrees,
            'form' => $form->createView()
        ]);
        */

        $sorties= $sortieRepository->findAllSorties();
        return $this->render('sorties/liste.html.twig', ["sorties"=>$sorties]);

    }

    #[Route('/sorties/detail/{id}', name: 'sortie_detail')]

    public function detailSortie($id,
                                 SortieRepository $sortieRepository,
                                 /*UtilisateurRepository $utilisateurRepository,
                                 CampusRepository $campusRepository,
                                 LieuRepository $lieuRepository,
                                 VilleRepository $villeRepository*/) : Response
    {
        $sortie = $sortieRepository->find($id);
        /*
        $participants = $utilisateurRepository->find($sortie->getParticipants());
        $campus = $campusRepository->find($sortie->getCampus());
        $lieu = $lieuRepository->find($sortie->getLieu());
        $ville = $villeRepository->find($lieu->getVille());
        */
        return $this->render('sorties/detail.html.twig',
                            ["sortie"=>$sortie
                             /*participants"=>$participants,
                             "campus"=>$campus,
                             "lieu"=>$lieu,
                             "ville"=>$ville*/]);
    }



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
        $lieux = $repositoryLieu->findBy(['ville'=>$request->request->get('ville')]);

        $sortie->setOrganisateur($this->getUser());
        $creationSortieForm = $this->createForm(CreationSortieType::class, $sortie);

        $creationSortieForm -> handleRequest($request);
        if ($creationSortieForm->isSubmitted() && $creationSortieForm->isValid()){
            $repositoryEtat = $this->getDoctrine()->getRepository(Etat::class);
            $etat = $repositoryEtat->findOneBy(['libelle'=>'Cr????e']);
            $sortie->setEtat($etat);
            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash('success', 'Sortie ajout??e !');

        }

        return $this->render('creation/create.html.twig', [
            'creationSortieForm' => $creationSortieForm->createView()
        ]);
    }

}