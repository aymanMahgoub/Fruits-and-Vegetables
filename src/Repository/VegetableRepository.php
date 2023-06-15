<?php

namespace App\Repository;

use App\Document\Vegetable;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use Doctrine\Bundle\MongoDBBundle\Repository\ServiceDocumentRepository;
use Doctrine\ODM\MongoDB\MongoDBException;

class VegetableRepository extends ServiceDocumentRepository
{

    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vegetable::class);
    }

    /**
     * @param Vegetable $vegetable
     * @param bool $flush
     * @return void
     * @throws MongoDBException
     */
    public function save(Vegetable $vegetable, bool $flush = false): void
    {
        $this->dm->persist($vegetable);

        if ($flush) {
            $this->dm->flush();
        }
    }

    /**
     * @param Vegetable $vegetable
     * @param bool $flush
     * @return void
     * @throws MongoDBException
     */
    public function remove(Vegetable $vegetable, bool $flush = false): void
    {
        $this->dm->remove($vegetable);

        if ($flush) {
            $this->dm->flush();
        }
    }


    /**
     * @param string $name
     * @return Vegetable|null
     */
    public function findByName(string $name): ?Vegetable
    {
        return $this->findOneBy(['name' => $name]);
    }
}
