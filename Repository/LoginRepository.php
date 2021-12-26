<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\AuthBundle\Repository;

use EveryWorkflow\AuthBundle\Document\LoginDocument;
use EveryWorkflow\MongoBundle\Repository\BaseDocumentRepository;
use EveryWorkflow\MongoBundle\Support\Attribute\RepositoryAttribute;

#[RepositoryAttribute(documentClass: LoginDocument::class)]
class LoginRepository extends BaseDocumentRepository implements LoginRepositoryInterface
{
    // Something
}
