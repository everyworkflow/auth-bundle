<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

namespace EveryWorkflow\AuthBundle\EventListener;

use EveryWorkflow\AuthBundle\Security\AuthUser;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

class JWTCreatedListener
{
    public function onJWTCreated(JWTCreatedEvent $event)
    {
        $payload = $event->getData();
        $user = $event->getUser();

        if ($user instanceof AuthUser) {
            $payload['first_name'] = $user->getData('first_name');
            $payload['last_name'] = $user->getData('last_name');
            $payload['session_token'] = $user->getData('session_token');
        }

        $event->setData($payload);
    }
}
