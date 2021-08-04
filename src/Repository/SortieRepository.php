<?php

namespace App\Repository;

use App\Entity\Sortie;
use App\Entity\Utilisateur;
use App\Filtre\SortieFiltre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

    public function findAllSorties() :?array
    {
        $queryBuilder = $this->createQueryBuilder('s');

        $queryBuilder -> join('s.campus','camp') -> addSelect('camp');
        $queryBuilder -> join('s.lieu','lieu') -> addSelect('lieu');
        $queryBuilder -> join('s.participants','part') -> addSelect('part');
        $queryBuilder -> join('s.etat','etat') -> addSelect('etat');
        $queryBuilder -> join('s.organisateur','orga') -> addSelect('orga');

        $queryBuilder ->addOrderBy('s.dateHeureDebut','DESC');

        $query = $queryBuilder -> getQuery();
        $sorties = $query -> getResult();
        return $sorties;
    }

    /**
     * Récupère les sorties en lien avec une recherche
     */
    public function findSearch(Utilisateur $user,
                               SortieFiltre $sortieFiltre)
    {

        $queryBuilder = $this->createQueryBuilder('s');

        $queryBuilder -> join('s.campus','camp') -> addSelect('camp');
        $queryBuilder -> join('s.lieu','lieu') -> addSelect('lieu');
        $queryBuilder -> join('s.participants','part') -> addSelect('part');
        $queryBuilder -> join('s.etat','etat') -> addSelect('etat');
        $queryBuilder -> join('s.organisateur','orga') -> addSelect('orga');

        $queryBuilder ->addOrderBy('s.dateHeureDebut','DESC');


        if (!empty($sortieFiltre->campus)) {
            $queryBuilder = $queryBuilder
                ->andWhere('camp.id IN (:campus)')
                ->setParameter('campus', $sortieFiltre->campus);
        }

        if (!empty($sortieFiltre->recherche)) {
            $queryBuilder = $queryBuilder
                ->andWhere('s.name LIKE :recherche')
                ->setParameter('recherche', "%{$sortieFiltre->recherche}%");
        }

        if (!empty($sortieFiltre->dateMin)) {
            $queryBuilder = $queryBuilder
                ->andWhere('s.dateHeureDebut >= :dateMin')
                ->setParameter('dateMin', $sortieFiltre->dateMin);
        }

        if (!empty($sortieFiltre->dateMax)) {
            $queryBuilder = $queryBuilder
                ->andWhere('s.dateHeureDebut <= :dateMax')
                ->setParameter('dateMax', $sortieFiltre->dateMax);
        }


        if (!empty($sortieFiltre->organisateur)) {
            $queryBuilder = $queryBuilder
                ->andWhere('s.organisateur = :organisateur')
                ->setParameter('organisateur', $user->getPseudo());
        }
        /*
        if (!empty($sortieFiltre->inscrit)) {
            $queryBuilder = $queryBuilder
                ->andWhere(':');
        }
        if (!empty($sortieFiltre->pasInscrit)) {
            $queryBuilder = $queryBuilder
                ->andWhere('s.organisateur = 1');
        }
        if (!empty($sortieFiltre->sortiesPassees)) {
            $queryBuilder = $queryBuilder
                ->andWhere('s. = 1');
        }
        */


        $query = $queryBuilder -> getQuery();
        $sorties = $query -> getResult();
        return $sorties;
    }

    // /**
    //  * @return Sortie[] Returns an array of Sortie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Sortie
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
