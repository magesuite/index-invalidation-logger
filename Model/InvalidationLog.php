<?php

namespace MageSuite\IndexInvalidationLogger\Model;

class InvalidationLog extends \Magento\Framework\Model\AbstractModel implements \MageSuite\Cache\Api\Data\CleanupLogInterface
{
    protected function _construct()
    {
        $this->_init(\MageSuite\IndexInvalidationLogger\Model\ResourceModel\InvalidationLog::class);
    }
}
