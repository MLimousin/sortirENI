<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\CreationSortieType;
use http\Env\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SortiesController extends AbstractController
{
    #[Route('/sorties', name: 'app_sorties')]

    public function sorties()
    {
        return $this->render('sorties/sorties.html.twig');

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