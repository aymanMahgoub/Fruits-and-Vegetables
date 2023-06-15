<?php

namespace App\Service;

use App\Constant\ItemConstant;

class StorageService
{
    /** @var FruitVegetableValidatorService $fruitVegetableValidatorService */
    private FruitVegetableValidatorService $fruitVegetableValidatorService;

    /** @var StorageHandlerFactory $storageHandlerFactory */
    private StorageHandlerFactory $storageHandlerFactory;

    /**
     * @param FruitVegetableValidatorService $fruitVegetableValidatorService
     * @param StorageHandlerFactory $storageHandlerFactory
     */
    public function __construct(
        FruitVegetableValidatorService $fruitVegetableValidatorService,
        StorageHandlerFactory $storageHandlerFactory
    ) {
        $this->fruitVegetableValidatorService = $fruitVegetableValidatorService;
        $this->storageHandlerFactory          = $storageHandlerFactory;
    }

    /**
     * @param String $request
     * @return void
     */
    public function bulkSave(String $request): void
    {
        $data = json_decode($request, true);
        foreach ($data as $item) {
            $this->saveItem($item);
        }
    }

    /**
     * @param array $item
     * @return void
     */
    public function saveItem(array $item): void
    {
        $this->fruitVegetableValidatorService->validateItem($item);
        $storageHandler = $this->storageHandlerFactory->getStorageHandlerByType($item[ItemConstant::TYPE]);
        $storageHandler->save($item);
    }
}
