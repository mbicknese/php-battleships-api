<?php

namespace App\Controller;

use App\Entity\Item;
use App\Repository\ItemRepository;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\HttpFoundation\Response;

class ItemController
{
    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var ItemRepository
     */
    private $itemRepository;

    /**
     * DefaultController constructor.
     *
     * @param \Twig_Environment $twig
     * @param ItemRepository $itemRepository
     */
    public function __construct(\Twig_Environment $twig, ItemRepository $itemRepository)
    {
        $this->twig = $twig;
        $this->itemRepository = $itemRepository;
    }

    /**
     * @return Response
     */
    public function index()
    {
        $items = $this->itemRepository->findAll();

        return new Response(
            $this->twig->render('Item/index.html.twig', compact('items'))
        );
    }
}
