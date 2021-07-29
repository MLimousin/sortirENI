<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SortiesController extends AbstractController
{
    #[Route('/Sorties', name: 'app_Sorties')]

    public function sorties()
    {
        return $this->render('sorties/sorties.html.twig');

    }

}