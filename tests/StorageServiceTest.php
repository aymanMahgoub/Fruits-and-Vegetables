<?php

namespace App\Tests;

use App\Repository\FruitRepository;
use App\Service\StorageService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class StorageServiceTest extends KernelTestCase
{
    /** @var StorageService $storageService */
    private StorageService $storageService;

    /** @var FruitRepository $fruitRepository */
    private FruitRepository $fruitRepository;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();
        $container             = static::getContainer();
        $this->storageService  = $container->get(StorageService::class);
        $this->fruitRepository = $container->get(FruitRepository::class);
    }

    public function testBulkSave(): void
    {
        $request = file_get_contents('request.json');
        $this->storageService->bulkSave($request);
        $fruit = $this->fruitRepository->findByName('Apples');
        $this->assertEquals($fruit->getName(), 'Apples');
    }
}
