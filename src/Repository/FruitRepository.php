<?php

namespace App\Repository;

use App\Document\Fruit;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use Doctrine\Bundle\MongoDBBundle\Repository\ServiceDocumentRepository;
use Doctrine\ODM\MongoDB\MongoDBException;

class FruitRepository extends ServiceDocumentRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fruit::class);
    }

    /**
     * @param Fruit $fruit
     * @param bool $flush
     * @return void
     * @throws MongoDBException
     */
    public function save(Fruit $fruit, bool $flush = false): void
    {
        $this->dm->persist($fruit);

        if ($flush) {
            $this->dm->flush();
        }
    }

    /**
     * @param Fruit $fruit
     * @param bool $flush
     * @return void
     * @throws MongoDBException
     */
    public function remove(Fruit $fruit, bool $flush = false): void
    {
        $this->dm->remove($fruit);

        if ($flush) {
            $this->dm->flush();
        }
    }

    /**
     * @param string $name
     * @return Fruit|null
     */
    public function findByName(string $name): ?Fruit
    {
        return $this->findOneBy(['name' => $name]);
    }
}
