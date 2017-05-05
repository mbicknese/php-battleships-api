<?php
namespace App\Controller;

use App\Model\Grid;
use App\Model\Match\Match;
use App\Security\MatchPlayer;
use App\Uid64\Uid64;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Validation;

/**
 * Class ShotController
 *
 * @package App\Controller
 * @author  Maarten Bicknese <maarten.bicknese@devmob.com>
 */
class ShotController
{
    private $validator;

    /**
     * ShotController constructor.
     */
    public function __construct()
    {
        $this->validator = Validation::createValidator();
    }

    /**
     * @param UserInterface|MatchPlayer $matchPlayer
     * @param Request $request
     * @return Response
     */
    public function fire(UserInterface $matchPlayer, Request $request): Response
    {
        if ($matchPlayer->getMatch()->phase()->phase() !== Match::PHASE_PLAYING) {
            return new Response('', 403);
        }

        /** @var Grid $grid */
        $grid = $matchPlayer->getMatch()->grid();
        $constraint = new Collection([
            'x' => new Range(['min' => 0, 'max' => $grid->width()]),
            'y' => new Range(['min' => 0, 'max' => $grid->height()]),
        ]);
        $violationList = $this->validator->validate($request->request->all(), $constraint);
        if (count($violationList) > 0) {
            return new Response('For once, please, stick to the rules!', 400);
        }

        $id = Uid64::toText($matchPlayer->getMatch()->id());
        return new Response('', 201, ['Location' => sprintf('/match/%s', $id)]);
    }
}
