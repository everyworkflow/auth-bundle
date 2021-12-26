<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\AuthBundle\Repository;

use EveryWorkflow\AuthBundle\Document\AuthTokenDocument;
use EveryWorkflow\MongoBundle\Repository\BaseDocumentRepository;
use EveryWorkflow\MongoBundle\Support\Attribute\RepositoryAttribute;

#[RepositoryAttribute(documentClass: AuthTokenDocument::class)]
class AuthTokenRepository extends BaseDocumentRepository implements AuthTokenRepositoryInterface
{
    // Something
}
