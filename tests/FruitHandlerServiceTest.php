<?php

namespace App\Tests;

use App\Constant\ItemConstant;
use App\Service\FruitHandlerService;
use ReflectionClass;
use ReflectionMethod;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FruitHandlerServiceTest extends KernelTestCase
{

    /** @var FruitHandlerService $fruitHandlerService */
    private FruitHandlerService $fruitHandlerService;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();
        $container                  = static::getContainer();
        $this->fruitHandlerService  = $container->get(FruitHandlerService::class);
    }

    public function testConvertFromGramToKiloGram(): void
    {
        $quantityResult = $this->getGramQuantityFunction()->invokeArgs($this->fruitHandlerService, [3, ItemConstant::kILO_GRAM_UNIT]);
        $this->assertEquals($quantityResult, 3000);
    }

    private function getGramQuantityFunction(): ReflectionMethod
    {
        $fruitHandlerServiceReflection = new ReflectionClass($this->fruitHandlerService);
        $getGramQuantityMethod         = $fruitHandlerServiceReflection->getMethod('getGramQuantity');
        $getGramQuantityMethod->setAccessible(true);

        return $getGramQuantityMethod;
    }
}
