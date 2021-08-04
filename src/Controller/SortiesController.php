<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Filtre\SortieFiltre;
use App\Form\CreationSortieType;
use App\Form\SortieFiltreFormType;
use App\Repository\CampusRepository;
use App\Repository\LieuRepository;
use App\Repository\SortieRepository;
use App\Repository\UtilisateurRepository;
use App\Repository\VilleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortiesController extends AbstractController
{
    #[Route('/sorties', name: 'app_sorties')]

    public function sorties()
    {
        return $this->render('sorties/sorties.html.twig');

    }

    #[Route('/sorties/liste', name: 'sortie_liste')]

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
                                 UtilisateurRepository $utilisateurRepository,
                                 CampusRepository $campusRepository,
                                 LieuRepository $lieuRepository,
                                 VilleRepository $villeRepository) : Response
    {
        $sortie = $sortieRepository->find($id);
        $participants = $utilisateurRepository->find($sortie->getParticipants());
        $campus = $campusRepository->find($sortie->getCampus());
        $lieu = $lieuRepository->find($sortie->getLieu());
        $ville = $villeRepository->find($lieu->getVille());

        return $this->render('sorties/detail.html.twig',
                            ["sortie"=>$sortie,
                             "participants"=>$participants,
                             "campus"=>$campus,
                             "lieu"=>$lieu,
                             "ville"=>$ville]);
    }



    #[Route('/create', name: 'app_creation')]

    public function create(Request $request): \Symfony\Component\HttpFoundation\Response {
        $sortie = new Sortie();
        $creationSortieForm = $this->createForm(CreationSortieType::class, $sortie);

        return $this->render('creation/create.html.twig', [
            'creationSortieForm' => $creationSortieForm->createView()
        ]);
    }

}