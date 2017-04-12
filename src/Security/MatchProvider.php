<?php
namespace App\Security;

use App\Model\Match\MatchId;
use App\Service\FindMatchService;
use App\Uid64\Uid64;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * Class MatchProvider
 *
 * @package App\Security
 * @author  Maarten Bicknese <maarten.bicknese@devmob.com>
 */
class MatchProvider implements UserProviderInterface
{
    /**
     * @var FindMatchService
     */
    private $findMatchService;

    /**
     * MatchProvider constructor.
     * @param FindMatchService $findMatchService
     */
    public function __construct(FindMatchService $findMatchService)
    {
        $this->findMatchService = $findMatchService;
    }

    /**
     * @param string $matchId Text representation of the match id
     *
     * @return Match
     *
     * @throws UsernameNotFoundException if the user is not found
     */
    public function loadUserByUsername($matchId): Match
    {
        try {
            $match = $this->findMatchService->execute(new MatchId(Uid64::fromText($matchId)));
        } catch (EntityNotFoundException $entityNotFoundException) {
            throw new UsernameNotFoundException();
        }

        return new Match($match);
    }

    /**
     * Refreshes the user for the account interface.
     *
     * It is up to the implementation to decide if the user data should be
     * totally reloaded (e.g. from the database), or if the UserInterface
     * object can just be merged into some internal array of users / identity
     * map.
     *
     * @param UserInterface $user
     *
     * @return UserInterface
     *
     * @throws UnsupportedUserException if the account is not supported
     */
    public function refreshUser(UserInterface $user)
    {
        return $user;
    }

    /**
     * Whether this provider supports the given user class.
     *
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass($class)
    {
        return true;
    }
}
