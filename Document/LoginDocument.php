<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\AuthBundle\Document;

use EveryWorkflow\MongoBundle\Document\BaseDocument;
use EveryWorkflow\MongoBundle\Document\HelperTrait\CreatedUpdatedHelperTrait;
use EveryWorkflow\MongoBundle\Document\HelperTrait\StatusHelperTrait;

class LoginDocument extends BaseDocument implements LoginDocumentInterface
{
    use CreatedUpdatedHelperTrait, StatusHelperTrait;
}
