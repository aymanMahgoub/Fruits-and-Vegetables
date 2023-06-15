<?php

namespace App\Tests;

use App\Service\StorageService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class StorageServiceTest extends KernelTestCase
{
    /** @var StorageService $storageService */
    private StorageService $storageService;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();
        $container                = static::getContainer();
        $this->storageService    = $container->get(StorageService::class);
    }

    public function testBulkSave(): void
    {
        $request = file_get_contents('request.json');
        $this->storageService->bulkSave($request);
        $this->assertTrue(true);
    }
}
