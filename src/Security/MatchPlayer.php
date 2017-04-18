<?php
namespace App\Security;

use Symfony\Component\Security\Core\User\UserInterface;
use App\Model\Match\Match as MatchModel;

/**
 * Class MatchPlayer
 *
 * @package App\Security
 * @author  Maarten Bicknese <maarten.bicknese@devmob.com>
 */
class MatchPlayer implements UserInterface
{
    /**
     * @var MatchModel
     */
    private $match;
    /**
     * @var int
     */
    private $sequence;

    /**
     * Match constructor.
     * @param MatchModel $match
     * @param int        $sequence (optional)
     */
    public function __construct(MatchModel $match, int $sequence = null)
    {
        $this->match = $match;
        $this->sequence = $sequence;
    }

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles(): array
    {
        return ['ROLE_PLAYER'];
    }

    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text
     * password will be salted, encoded, and then compared to this value.
     *
     * @return string The password
     */
    public function getPassword()
    {
        return '';
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return '';
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        return $this->match->id();
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
    }

    /**
     * @return int
     */
    public function getSequence(): int
    {
        return $this->sequence;
    }

    /**
     * @param int $sequence
     */
    public function setSequence(int $sequence)
    {
        $this->sequence = $sequence;
    }
}
