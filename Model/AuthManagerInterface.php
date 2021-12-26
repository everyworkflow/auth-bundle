<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\AuthBundle\Model;

use Exception;

interface AuthManagerInterface
{
    /**
     * @throws Exception
     */
    public function session(string $username, string $password): array;

    /**
     * @throws Exception
     */
    public function JWT(string $sessionToken, string $sessionName = 'Not defined'): array;

    /**
     * @throws Exception
     */
    public function refreshJWT(string $session_token, string $refresh_token): array;
}
