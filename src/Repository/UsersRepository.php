<?php

namespace App\Repository;

use App\Entity\Users;
use App\Dto\UsersDto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Users>
 */
class UsersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Users::class);
    }

    public function findAll(): array
    {
        $users = $this->findBy([]);
        return array_map(function ($user) {
            return new UsersDto(
                $user->getId(),
                $user->getName(),
                $user->getFirstname(),
                $user->getLastname(),
                $user->getEmail(),
                $user->getNumber(),
                $user->getPicture(),
                $user->getContenthash(),
                $user->getRoles()
            );
        }, $users);
    }

//    /**
//     * @return Users[] Returns an array of Users objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

   public function findUser($email): ?Users
   {
       return $this->createQueryBuilder('u')
           ->andWhere('u.email = :email')
           ->setParameter('email', $email)
           ->getQuery()
           ->getOneOrNullResult()
       ;
   }
}
