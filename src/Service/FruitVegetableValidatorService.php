<?php

namespace App\Service;

use App\Constant\ItemConstant;
use InvalidArgumentException;

class FruitVegetableValidatorService
{
    private const REQUIRED_KEYS = [
//        ItemConstant::ID,
        ItemConstant::NAME,
        ItemConstant::UNIT,
        ItemConstant::QUANTITY,
        ItemConstant::TYPE,
    ];

    /**
     * @param array $item
     * @throws InvalidArgumentException
     * @return void
     */
    public function validateItem(array $item): void
    {
        if (array_diff_key(array_flip(self::REQUIRED_KEYS), $item)) {
            throw new InvalidArgumentException('please add missing keys');
        }
    }

}
