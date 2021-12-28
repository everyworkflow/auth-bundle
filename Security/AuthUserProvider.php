<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\AuthBundle\Security;

use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthUserProvider implements AuthUserProviderInterface
{
    protected AuthUserInterface $authUser;

    public function __construct(
        AuthUserInterface $authUser
    ) {
        $this->authUser = $authUser;
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        return $this->loadUserByIdentifierAndPayload($identifier, []);
    }

    /**
     * Load a user by its username, including the JWT token payload.
     *
     * @throws UsernameNotFoundException|UserNotFoundException if the user is not found
     *
     * @deprecated since 2.12, implement loadUserByIdentifierAndPayload() instead.
     */
    public function loadUserByUsernameAndPayload(string $username, array $payload): UserInterface
    {
        return $this->loadUserByIdentifierAndPayload($username, $payload);
    }

    /**
     * {@inheritdoc}
     *
     * @param array $payload The JWT payload from which to create an instance
     */
    public function loadUserByIdentifierAndPayload(string $identifier, array $payload): UserInterface
    {
        $user = (new AuthUser($payload));
        $this->authUser->resetData($payload);
        return $user;
    }

    /**
     * Refreshes the user after being reloaded from the session.
     *
     * When a user is logged in, at the beginning of each request, the
     * User object is loaded from the session and then this method is
     * called. Your job is to make sure the user's data is still fresh by,
     * for example, re-querying for fresh User data.
     *
     * If your firewall is "stateless: true" (for a pure API), this
     * method is not called.
     *
     * @return UserInterface
     */
    public function refreshUser(UserInterface $user)
    {
        return $user;
        
        echo 'refressh';
        exit;

        if (!$user instanceof AuthUserInterface) {
            throw new UnsupportedUserException(sprintf('Invalid user class "%s".', get_class($user)));
        }

        // Return a User object after making sure its data is "fresh".
        // Or throw a UserNotFoundException if the user no longer exists.
        throw new \Exception('TODO: fill in refreshUser() inside ' . __FILE__);
    }

    /**
     * Tells Symfony to use this provider for this User class.
     */
    public function supportsClass(string $class): bool
    {
        return AuthUser::class === $class || is_subclass_of($class, AuthUser::class);
    }
}
