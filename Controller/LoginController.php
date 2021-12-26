<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\AuthBundle\Controller;

use EveryWorkflow\AuthBundle\Model\AuthManagerInterface;
use EveryWorkflow\CoreBundle\Annotation\EwRoute;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class LoginController extends AbstractController
{
    protected AuthManagerInterface $authManager;

    public function __construct(AuthManagerInterface $authManager)
    {
        $this->authManager = $authManager;
    }

    #[EwRoute(
        path: "auth/login",
        name: 'auth.login',
        methods: 'POST',
        swagger: [
            'description' => 'Login with credentials.',
            'requestBody' => [
                'content' => [
                    'application/json' => [
                        'schema' => [
                            'properties' => [
                                'username' => [
                                    'default' => 'test@example.com',
                                    'type' => 'string',
                                    'required' => true,
                                ],
                                'password' => [
                                    'default' => 'test@123',
                                    'type' => 'string',
                                    'required' => true,
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            'responses' => [
                '200' => [
                    'description' => 'Success',
                    'content' => [
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'session_token' => [
                                        'type' => 'string',
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
                '401' => [
                    'description' => 'Invalid credentials.',
                    'content' => [
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'title' => [
                                        'type' => 'string',
                                    ],
                                    'status' => [
                                        'type' => 'integer',
                                    ],
                                    'detail' => [
                                        'type' => 'string',
                                    ],
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]
    )]
    public function login(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['username']) || !isset($data['password'])) {
            return new JsonResponse([
                'title' => 'An error occurred',
                'status' => 401,
                'detail' => 'Enter valid credentials.'
            ], 401);
        }

        try {
            $sessionData = $this->authManager->session($data['username'], $data['password']);
            return new JsonResponse($sessionData);
        } catch (\Exception $e) {
            return new JsonResponse([
                'title' => 'An error occurred',
                'status' => 400,
                'detail' => $e->getMessage()
            ], 400);
        }
    }

    #[EwRoute(
        path: "auth/login/session",
        name: 'auth.login.session',
        methods: 'POST',
        swagger: [
            'description' => 'Start JWT session with session token.',
            'requestBody' => [
                'content' => [
                    'application/json' => [
                        'schema' => [
                            'properties' => [
                                'session_token' => [
                                    'type' => 'string',
                                    'required' => true,
                                ],
                                'session_name' => [
                                    'default' => 'Test session',
                                    'type' => 'string',
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            'responses' => [
                '200' => [
                    'description' => 'Success',
                    'content' => [
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'session_token' => [
                                        'type' => 'string',
                                    ],
                                    'refresh_token' => [
                                        'type' => 'string',
                                    ],
                                    'token' => [
                                        'type' => 'string',
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
                '401' => [
                    'description' => 'Invalid session token.',
                    'content' => [
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'title' => [
                                        'type' => 'string',
                                    ],
                                    'status' => [
                                        'type' => 'integer',
                                    ],
                                    'detail' => [
                                        'type' => 'string',
                                    ],
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]
    )]
    public function session(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['session_token'])) {
            return new JsonResponse([
                'title' => 'An error occurred',
                'status' => 401,
                'detail' => 'Invalid session token.'
            ], 401);
        }

        try {
            $jwtData = $this->authManager->JWT($data['session_token'], $data['session_name'] ?? 'Not defined');
            return new JsonResponse($jwtData);
        } catch (\Exception $e) {
            return new JsonResponse([
                'title' => 'An error occurred',
                'status' => 401,
                'detail' => 'Invalid session token.'
            ], 401);
        }
    }

    #[EwRoute(
        path: "auth/login/refresh",
        name: 'auth.login.refresh',
        methods: 'POST',
        swagger: [
            'description' => 'Refresh JWT token with session_token and refresh_token.',
            'requestBody' => [
                'content' => [
                    'application/json' => [
                        'schema' => [
                            'properties' => [
                                'session_token' => [
                                    'type' => 'string',
                                    'required' => true,
                                ],
                                'refresh_token' => [
                                    'type' => 'string',
                                    'required' => true,
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            'responses' => [
                '200' => [
                    'description' => 'Success',
                    'content' => [
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'session_token' => [
                                        'type' => 'string',
                                    ],
                                    'refresh_token' => [
                                        'type' => 'string',
                                    ],
                                    'token' => [
                                        'type' => 'string',
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
                '401' => [
                    'description' => 'Invalid session token or refresh token.',
                    'content' => [
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'title' => [
                                        'type' => 'string',
                                    ],
                                    'status' => [
                                        'type' => 'integer',
                                    ],
                                    'detail' => [
                                        'type' => 'string',
                                    ],
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]
    )]
    public function refresh(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['session_token']) || !isset($data['refresh_token'])) {
            return new JsonResponse([
                'title' => 'An error occurred',
                'status' => 401,
                'detail' => 'Invalid session token or refresh token.'
            ], 401);
        }

        try {
            $jwtData = $this->authManager->refreshJWT($data['session_token'], $data['refresh_token']);
            return new JsonResponse($jwtData);
        } catch (\Exception $e) {
            return new JsonResponse([
                'title' => 'An error occurred',
                'status' => 401,
                'detail' => $e->getMessage()
            ], 401);
        }
    }
}
