<?php

namespace App\Service;

use App\Constant\ItemConstant;

class StorageHandlerFactory
{
    /** @var FruitHandlerService $fruitHandlerService */
    private FruitHandlerService $fruitHandlerService;

    /** @var VegetablesHandlerService $vegetablesHandlerService */
    private VegetablesHandlerService $vegetablesHandlerService;

    /**
     * @param FruitHandlerService $fruitHandlerService
     * @param VegetablesHandlerService $vegetablesHandlerService
     */
    public function __construct(
        FruitHandlerService $fruitHandlerService,
        VegetablesHandlerService $vegetablesHandlerService,
    ){
        $this->fruitHandlerService      = $fruitHandlerService;
        $this->vegetablesHandlerService = $vegetablesHandlerService;
    }

    /**
     * @param string $type
     * @return StorageHandler
     */
    public function getStorageHandlerByType(string $type): StorageHandler
    {
        return match ($type) {
            ItemConstant::FRUIT_TYPE     => $this->fruitHandlerService,
            ItemConstant::VEGETABLE_TYPE => $this->vegetablesHandlerService,
        };
    }
}