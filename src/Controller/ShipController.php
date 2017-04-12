<?php
namespace App\Controller;

use App\Service\FindMatchService;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ShipController
 *
 * @package App\Controller
 * @author  Maarten Bicknese <maarten.bicknese@devmob.com>
 */
class ShipController
{
    /**
     * @var FindMatchService
     */
    private $findMatchService;

    /**
     * ShipController constructor.
     * @param FindMatchService $findMatchService
     */
    public function __construct(FindMatchService $findMatchService)
    {
        $this->findMatchService = $findMatchService;
    }

    /**
     * @return Response
     */
    public function place(): Response
    {


        return new Response();
    }
}
