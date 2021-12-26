<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\AuthBundle\Repository;

use EveryWorkflow\AuthBundle\Document\LoginSessionDocument;
use EveryWorkflow\MongoBundle\Repository\BaseDocumentRepository;
use EveryWorkflow\MongoBundle\Support\Attribute\RepositoryAttribute;

#[RepositoryAttribute(documentClass: LoginSessionDocument::class)]
class LoginSessionRepository extends BaseDocumentRepository implements LoginSessionRepositoryInterface
{
    // Something
}
