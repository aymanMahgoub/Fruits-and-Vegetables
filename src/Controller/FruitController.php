<?php

namespace App\Controller;

use App\Constant\ItemConstant;
use App\Service\FruitHandlerService;
use App\Service\StorageService;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class FruitController extends AbstractController
{
    /** @var FruitHandlerService $fruitHandlerService */
    private FruitHandlerService $fruitHandlerService;

    public function __construct(
        FruitHandlerService $fruitHandlerService
    ){
        $this->fruitHandlerService = $fruitHandlerService;
    }

    #[Route('/fruit', name: 'list_all_fruits', methods: ['GET'])]
    public function list(Request $request): JsonResponse
    {
        $unit = $request->query->get('unit') ?? ItemConstant::GRAM_UNIT;
        return $this->json([
            'fruits' => $this->fruitHandlerService->listAllFruits($unit),
        ], Response::HTTP_OK);
    }

    #[Route('/fruit/add', name: 'add_fruit', methods: ['POST'])]
    public function add(Request $request, StorageService $storageService): JsonResponse
    {
        $fruit = json_decode($request->getContent(), true);

        try {
            $storageService->saveItem($fruit);
        }catch (InvalidArgumentException) {
            return $this->json([
                'message' => 'Missing fruit data',
            ], Response::HTTP_BAD_REQUEST);
        }

        return $this->json([
            'message' => 'Fruit saved successfully',
        ], Response::HTTP_OK);
    }

    #[Route('/fruit/{id}', name: 'remove_fruit', methods: ['POST'])]
    public function remove(int $id): JsonResponse
    {
        try {
            $this->fruitHandlerService->removeFruit($id);
        } catch (NotFoundHttpException) {
            return $this->json([
                'message' => 'There is no fruit with this id',
            ], Response::HTTP_NOT_FOUND);
        }

        return $this->json([
            'message' => 'fruit removed successfully',
        ], Response::HTTP_OK);
    }

    #[Route('/fruit-search', name: 'fruit_search', methods: ['GET'])]
    public function search(Request $request): JsonResponse
    {
        $quantity = $request->query->get('quantity');
        $name     = $request->query->get('name');
        $unit     = $request->query->get('unit') ?? ItemConstant::GRAM_UNIT;

        $fruits = $this->fruitHandlerService->search([
            'name' => $name,
            'quantity' => $quantity
        ], $unit);

        if (empty($fruits)) {
            return $this->json([
                'message' => 'There is no fruit with this search criteria',
            ], Response::HTTP_NOT_FOUND);
        }

       return $this->json([
               'fruits' => $fruits
       ], Response::HTTP_OK);
    }
}
