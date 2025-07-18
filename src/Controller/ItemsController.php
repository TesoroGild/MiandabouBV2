<?php

namespace App\Controller;

//Packages
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

//Application
use App\Entity\Items;
use App\Entity\Enums\ItemCategory;

final class ItemsController extends AbstractController
{
    //CREATE
    #[Route('/api/items', name: 'create_items', methods: ['POST'])]
    public function createItem(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $item = new Items();
        $category = ItemCategory::from($request->request->get('category'));

        $item->setName($request->request->get('name'));
        $item->setCategory($category);
        $item->setDescription($request->request->get('description'));
        $item->setPrice($request->request->get('price'));
        $item->setQuantity($request->request->get('qte'));
        $item->setVideo($request->request->get('video'));

        /*TODO*/
        //logique de hash?
        //$item->setPicture($data['picture']);
        //$item->setContenthash($data['contenthash']);

        $item->setTimecreated(new \DateTime());
        $item->setTimemodified(new \DateTime());

        // $entityManager->persist($item);
        // $entityManager->flush();

        // return $this->json([
        //     'msg' => 'Article ajouté!'
        // ], Response::HTTP_CREATED);
        
        return $this->json([
            'msg' => 'Article ajouté!', 
            'item' => $item
        ], Response::HTTP_CREATED);
    }

    //READ

    #[Route('/api/items', name: 'list_items', methods: ['GET'])]
    public function getItems(): JsonResponse
    {
        return $this->json([
            'items' => [],
            'msg' => 'Liste des articles!'
        ], Response::HTTP_OK);
    }

    //UPDATE

    //DELETE
}
