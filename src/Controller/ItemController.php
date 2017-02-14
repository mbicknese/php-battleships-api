<?php

namespace App\Controller;

use App\Entity\Item;
use App\Repository\ItemRepository;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\HttpFoundation\Response;

class ItemController
{
    /**
     * @var ItemRepository
     */
    private $itemRepository;

    /**
     * DefaultController constructor.
     *
     * @param ItemRepository $itemRepository
     */
    public function __construct(ItemRepository $itemRepository)
    {
        $this->itemRepository = $itemRepository;
    }

    /**
     * @return Response
     */
    public function index()
    {
        $items = $this->itemRepository->findAll();

        return new Response('<h1>Hello World!</h1>');
    }
}
