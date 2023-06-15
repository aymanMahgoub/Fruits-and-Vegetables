<?php

namespace App\Service;

use App\Constant\ItemConstant;
use App\Document\Fruit;
use App\Repository\FruitRepository;
use Doctrine\ODM\MongoDB\LockException;
use Doctrine\ODM\MongoDB\Mapping\MappingException;
use Doctrine\ODM\MongoDB\MongoDBException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FruitHandlerService extends StorageHandler
{
    /** @var FruitRepository $fruitRepository */
    private FruitRepository $fruitRepository;

    public function __construct(
        FruitRepository $fruitRepository,
    ){
        $this->fruitRepository = $fruitRepository;
    }

    /**
     * @param array $data
     * @return void
     * @throws MongoDBException
     */
    public function save(array $data): void
    {
        $fruit = $this->search($data[ItemConstant::NAME]);
        if ($fruit instanceof Fruit) {
            $quantity = $this->getGramQuantity($data[ItemConstant::QUANTITY], $data[ItemConstant::UNIT]);
            $fruit->setQuantity($fruit->getQuantity() + $quantity);
        } else {
            $fruit = $this->hydrateNewFruit($data);
        }

        $this->fruitRepository->save($fruit, true);
    }

    /**
     * @param array $data
     * @return Fruit
     */
    private function hydrateNewFruit(array $data): Fruit
    {
        $fruit = new Fruit();
        $fruit->setName($data[ItemConstant::NAME]);
        $quantity = $this->getGramQuantity($data[ItemConstant::QUANTITY], $data[ItemConstant::UNIT]);
        $fruit->setQuantity($quantity);

        return $fruit;
    }

    /**
     * @param int $id
     * @return void
     * @throws LockException
     * @throws MappingException
     * @throws MongoDBException
     */
    public function removeFruit(int $id): void
    {
        $fruit = $this->fruitRepository->find($id);
        if (!$fruit instanceof Fruit) {
            throw new NotFoundHttpException();
        }

        $this->fruitRepository->remove($fruit);

    }

    /**
     * @param string $unit
     * @return array
     */
    public function listAllFruits(string $unit = ItemConstant::GRAM_UNIT): array
    {
       $fruits = $this->fruitRepository->findAll();
       $list = [];
       /** @var Fruit $fruit */
        foreach ($fruits as $fruit) {
           $list[] = [
               'name'     => $fruit->getName(),
               'quantity' => $unit === ItemConstant::kILO_GRAM_UNIT ? $fruit->getQuantity() / 1000 : $fruit->getQuantity()
           ];
       }

        return $list;
    }

    /**
     * @param String $name
     * @return Fruit|null
     */
    public function search(String $name): ?Fruit
    {
        return $this->fruitRepository->findByName($name);
    }
}
