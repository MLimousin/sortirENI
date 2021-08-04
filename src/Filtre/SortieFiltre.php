<?php


namespace App\Filtre;


use App\Entity\Campus;
use Symfony\Component\Validator\Constraints as Assert;


class SortieFiltre
{
    /**
     * @var Campus[]
     */
    public $campus = [];

    /**
     * @var string
     */
    public $recherche = '';


    #[Assert\Date]
    public $dateMin;

    #[Assert\Date]
    public $dateMax;

    /**
     * @var boolean
     */
    public $organisateur = false;

    /**
     * @var boolean
     */
    public $inscrit = false;

    /**
     * @var boolean
     */
    public $pasInscrit = false;

    /**
     * @var boolean
     */
    public $sortiesPassees = false;
}