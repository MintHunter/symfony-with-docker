<?php

namespace App\Repository;

use App\Entity\MovieDataBase;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MovieDataBase|null find($id, $lockMode = null, $lockVersion = null)
 * @method MovieDataBase|null findOneBy(array $criteria, array $orderBy = null)
 * @method MovieDataBase[]    findAll()
 * @method MovieDataBase[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieDataBaseRepository extends ServiceEntityRepository
{
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, MovieDataBase::class);
	}

	// /**
	//  * @return MovieDataBase[] Returns an array of MovieDataBase objects
	//  */
	/*
	public function findByExampleField($value)
	{
		return $this->createQueryBuilder('e')
			->andWhere('e.exampleField = :val')
			->setParameter('val', $value)
			->orderBy('e.id', 'ASC')
			->setMaxResults(10)
			->getQuery()
			->getResult()
		;
	}
	*/

	/*
	public function findOneBySomeField($value): ?MovieDataBase
	{
		return $this->createQueryBuilder('e')
			->andWhere('e.exampleField = :val')
			->setParameter('val', $value)
			->getQuery()
			->getOneOrNullResult()
		;
	}
	*/
}
