<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\AuthBundle\Document;

use EveryWorkflow\MongoBundle\Document\BaseDocumentInterface;
use EveryWorkflow\MongoBundle\Document\HelperTrait\CreatedUpdatedHelperTraitInterface;
use EveryWorkflow\MongoBundle\Document\HelperTrait\StatusHelperTraitInterface;

interface LoginDocumentInterface extends BaseDocumentInterface, CreatedUpdatedHelperTraitInterface, StatusHelperTraitInterface
{
    // Something
}
