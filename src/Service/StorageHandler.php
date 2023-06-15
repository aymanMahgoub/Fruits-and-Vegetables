<?php

namespace App\Service;

use App\Constant\ItemConstant;

Abstract class StorageHandler
{
    abstract function save(array $data): void;

    /**
     * @param $quantity
     * @param $unit
     * @return float
     */
    protected function getGramQuantity($quantity, $unit): float
    {
        return $unit == ItemConstant::kILO_GRAM_UNIT
            ? $quantity * 1000 : $quantity;
    }
}