<?php

namespace App\Service;

use App\Constant\ItemConstant;
use App\Document\Fruit;
use App\Document\Vegetable;
use App\Repository\VegetableRepository;
use Doctrine\ODM\MongoDB\LockException;
use Doctrine\ODM\MongoDB\Mapping\MappingException;
use Doctrine\ODM\MongoDB\MongoDBException;

class VegetablesHandlerService extends StorageHandler
{
    /** @var VegetableRepository $vegetableRepository */
    private VegetableRepository $vegetableRepository;

    public function __construct(
        VegetableRepository $vegetableRepository
    ){
        $this->vegetableRepository = $vegetableRepository;
    }

    /**
     * @param array $data
     * @return void
     * @throws MongoDBException
     */
    public function save(array $data): void
    {
        $vegetable = $this->search($data[ItemConstant::NAME]);
        if ($vegetable instanceof Vegetable) {
            $quantity = $this->getGramQuantity($data[ItemConstant::QUANTITY], $data[ItemConstant::UNIT]);
            $vegetable->setQuantity($vegetable->getQuantity() + $quantity);
        } else {
            $vegetable = $this->hydrateNewVegetable($data);
        }

        $this->vegetableRepository->save($vegetable, true);
    }

    /**
     * @param array $data
     * @return Vegetable
     */
    private function hydrateNewVegetable(array $data): Vegetable
    {
        $vegetable = new Vegetable();
        $vegetable->setName($data[ItemConstant::NAME]);
        $quantity = $this->getGramQuantity($data[ItemConstant::QUANTITY], $data[ItemConstant::UNIT]);
        $vegetable->setQuantity($quantity);

        return $vegetable;
    }

    /**
     * @param int $id
     * @return void
     * @throws LockException
     * @throws MappingException
     * @throws MongoDBException
     */
    public function removeVegetable(int $id): void
    {
        $vegetable = $this->vegetableRepository->find($id);
        if ($vegetable instanceof Vegetable) {
            $this->vegetableRepository->remove($vegetable);
        }
    }

    /**
     * @param string $unit
     * @return array
     */
    public function listAllVegetables(string $unit = ItemConstant::GRAM_UNIT): array
    {
        $vegetables = $this->vegetableRepository->findAll();
        $list = [];
        /** @var Vegetable $vegetable */
        foreach ($vegetables as $vegetable) {
            $list[] = [
                'name'     => $vegetable->getName(),
                'quantity' => $unit === ItemConstant::kILO_GRAM_UNIT ? $vegetable->getQuantity() / 1000 : $vegetables->getQuantity()
            ];
        }

        return $list;
    }

    /**
     * @param String $name
     * @return Vegetable|null
     */
    public function search(String $name): ?Vegetable
    {
        return $this->vegetableRepository->findByName($name);
    }
}
