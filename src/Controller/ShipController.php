<?php
namespace App\Controller;

use App\Gameplay\Shipbuilder;
use App\Model\Match\Match;
use App\Security\MatchPlayer;
use App\Service\FindMatchService;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class ShipController
 *
 * @package App\Controller
 * @author  Maarten Bicknese <maarten.bicknese@devmob.com>
 */
class ShipController
{
    /**
     * @var string
     */
    public $phaseOverMessage;
    /**
     * @var FindMatchService
     */
    private $findMatchService;
    /**
     * @var ValidatorInterface
     */
    private $validator;
    /**
     * @var Shipbuilder
     */
    private $shipBuilder;

    /**
     * ShipController constructor.
     * @param FindMatchService $findMatchService
     * @param Shipbuilder $shipBuilder
     */
    public function __construct(FindMatchService $findMatchService, Shipbuilder $shipBuilder)
    {
        $this->findMatchService = $findMatchService;
        $this->validator = Validation::createValidator();

        if (!$this->phaseOverMessage) {
            $this->phaseOverMessage = implode(' ', [
                'There\'s a time to place ships and a time to blow up ships.',
                'You just blew your change to place ships.',
            ]);
        }
        $this->shipBuilder = $shipBuilder;
    }

    /**
     * @param UserInterface|MatchPlayer $matchPlayer
     * @param Request $request
     * @return Response
     */
    public function place(UserInterface $matchPlayer, Request $request): Response
    {
        if ($matchPlayer->getMatch()->phase() !== Match::PHASE_WAITING) {
            return new Response($this->phaseOverMessage, 403);
        }
        $grid = $matchPlayer->getMatch()->grid();
        $constraint = new Collection([
            'start_x' => new Range(['min' => 0, 'max' => $grid->width()]),
            'start_y' => new Range(['min' => 0, 'max' => $grid->height()]),
            'end_x'   => new Range(['min' => 0, 'max' => $grid->width()]),
            'end_y'   => new Range(['min' => 0, 'max' => $grid->height()]),
        ]);
        $violationList = $this->validator->validate($request->request->all(), $constraint);
        if (count($violationList) > 0) {
            return new Response('For once, please, stick to the rules!', 400);
        }

        try {
            $this->shipBuilder->build(
                $matchPlayer->getMatch(),
                $matchPlayer->getSequence(),
                $request->get('start_x'),
                $request->get('start_y'),
                $request->get('end_x'),
                $request->get('end_y')
            );
        } catch (RuntimeException $exception) {
            return new Response($exception->getMessage(), 400);
        }
        return new Response();
    }
}
