<?php

namespace App\Controller;

use App\Constant\ItemConstant;
use App\Service\StorageService;
use App\Service\VegetablesHandlerService;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class VegetableController extends AbstractController
{
    /** @var VegetablesHandlerService $vegetablesHandlerService */
    private VegetablesHandlerService $vegetablesHandlerService;

    public function __construct(
        VegetablesHandlerService $vegetablesHandlerService
    ){
        $this->vegetablesHandlerService = $vegetablesHandlerService;
    }

    #[Route('/vegetables', name: 'list_all_vegetables', methods: ['GET'])]
    public function list(Request $request): JsonResponse
    {
        $unit = $request->query->get('unit') ?? ItemConstant::GRAM_UNIT;

        return $this->json([
            'vegetables' => $this->vegetablesHandlerService->listAllVegetables($unit),
        ], Response::HTTP_OK);
    }

    #[Route('/vegetable/add', name: 'add_vegetable', methods: ['POST'])]
    public function add(Request $request, StorageService $storageService): JsonResponse
    {
        $vegetable = json_decode($request->getContent(), true);

        try {
            $storageService->saveItem($vegetable);
        }catch (InvalidArgumentException) {
            return $this->json([
                'message' => 'Missing vegetable data',
            ], Response::HTTP_BAD_REQUEST);
        }

        return $this->json([
            'message' => 'vegetable saved successfully',
        ], Response::HTTP_OK);
    }

    #[Route('/vegetable/{id}', name: 'remove_vegetable', methods: ['POST'])]
    public function remove(int $id): JsonResponse
    {
        try {
            $this->vegetablesHandlerService->removeVegetable($id);
        } catch (NotFoundHttpException) {
            return $this->json([
                'message' => 'There is no vegetable with this id',
            ], Response::HTTP_NOT_FOUND);
        }

        return $this->json([
            'message' => 'vegetable removed successfully',
        ], Response::HTTP_OK);
    }
}

