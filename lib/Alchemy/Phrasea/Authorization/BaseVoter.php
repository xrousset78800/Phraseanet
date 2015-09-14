<?php
/*
 * This file is part of Phraseanet
 *
 * (c) 2005-2015 Alchemy
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Alchemy\Phrasea\Authorization;

use Alchemy\Phrasea\Application;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

abstract class BaseVoter implements VoterInterface
{
    private $supportedAttributes;
    private $supportedClass;

    /** @var Application */
    private $app;

    public function __construct(Application $app, array $attributes, $supportedClass)
    {
        $this->app = $app;
        $this->supportedAttributes = $attributes;
        $this->supportedClass = $supportedClass;

        if (!is_callable([$this, 'isGranted'])) {
            throw new \LogicException('Subclasses should implement a "isGranted" method');
        }
    }

    public function supportsAttribute($attribute)
    {
        return in_array($attribute, $this->supportedAttributes);
    }

    public function supportsClass($class)
    {
        $supportedClass = $this->supportedClass;
        if ($class == $supportedClass || is_subclass_of($class, $supportedClass)) {
            return true;
        }

        return false;
    }

    public function vote(TokenInterface $token, $object, array $attributes)
    {
        if (!$object || !$this->supportsClass(get_class($object))) {
            return self::ACCESS_ABSTAIN;
        }

        $user = (ctype_digit($token->getUser())) ? new \User_Adapter((int) $token->getUser(), $this->app) : null;

        foreach ($attributes as $attribute) {
            $attribute = strtolower($attribute);

            if ($this->supportsAttribute($attribute)) {
                $isGranted = call_user_func([$this, 'isGranted'], $attribute, $object, $user);

                return $isGranted ? self::ACCESS_GRANTED : self::ACCESS_DENIED;
            }
        }

        return self::ACCESS_ABSTAIN;
    }

    /**
     * @param string             $attribute
     * @param object             $object
     * @param \User_Adapter|null $user
     * @return bool
     */
    //abstract protected function isGranted($attribute, $object, \User_Adapter $user = null);
}
