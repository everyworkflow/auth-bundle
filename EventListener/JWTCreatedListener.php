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
            $payload = array_merge($payload, $user->toArray());
        }

        $event->setData($payload);
    }
}
