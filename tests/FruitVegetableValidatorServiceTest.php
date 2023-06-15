<?php

namespace App\Tests;

use App\Service\FruitVegetableValidatorService;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FruitVegetableValidatorServiceTest extends KernelTestCase
{
    /** @var FruitVegetableValidatorService $fruitVegetableValidatorService */
    private FruitVegetableValidatorService $fruitVegetableValidatorService;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();
        $container                            = static::getContainer();
        $this->fruitVegetableValidatorService = $container->get(FruitVegetableValidatorService::class);
    }

    public function testNotValidItem(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->fruitVegetableValidatorService->validateItem([]);
    }
}
