<?php

namespace App\Repository;

use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

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


    public function rechecheFormulaire($site,$search,$dateDebut,$dateFin){

        if ($site){
            $liste=$this->createQueryBuilder('s')
                ->andWhere('s.site = :site')
                ->setParameter('site', $site)
                ->orderBy('s.nom', 'ASC')
                ->getQuery()
                ->getResult()
            ;
        }
        if ($search){
            $liste = $this->createQueryBuilder('s')
                ->where('s.nom like :nom')
                ->setParameter('nom', '%'.$search.'%')
                ->orderBy('s.nom', 'ASC')
                ->getQuery()
                ->getResult();
        }
        if ($dateDebut){

            $liste = $this ->createQueryBuilder('s')
                ->where('s.dateDebut >= :date')
                ->setParameter('date',$dateDebut)
                ->orderBy('s.nom', 'ASC')
                ->getQuery()
                ->getResult();
        }

        if ($dateFin){

        $liste = $this ->createQueryBuilder('s')
            ->where('s.dateDebut <= :date')
            ->setParameter('date',$dateFin)
            ->orderBy('s.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }



        return $liste;
    }

    public function rechercheParSite($site)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.site = :site')
            ->setParameter('site', $site)
            ->orderBy('s.nom', 'ASC')
            ->getQuery()
            ->getResult()
            ;

    }

    public function rechercheParSiteParRecherche($site,$search)
    {


        return $this->createQueryBuilder('s')
            ->where('s.site =:site')
            ->setParameter('site', $site)
            ->andWhere('s.nom like :nom')
            ->setParameter('nom', '%'.$search.'%')
            ->orderBy('s.nom', 'ASC')
            ->getQuery()
            ->getResult()
            ;

    }

    public function rechercheSiteNomDateDebut($site,$search,$dateDebut){

        return $this->createQueryBuilder('s')
            ->where('s.site =:site')
            ->setParameter('site', $site)
            ->andWhere('s.nom like :nom')
            ->setParameter('nom', $search)
            ->andWhere('s.dateDebut >=: debut')
            ->setParameter('debut', $dateDebut)
            ->orderBy('s.nom', 'ASC')
            ->getQuery()
            ->getResult()
            ;



    }

    public function recherche($search){
            return $this->createQueryBuilder('s')
            ->where('s.nom like :nom')
            ->setParameter('nom', '%'.$search.'%')
            ->orderBy('s.nom', 'ASC')
            ->getQuery()
            ->getResult()
        ;

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
