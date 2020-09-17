<?php

namespace MageSuite\IndexInvalidationLogger\Model;

class InvalidationLogRepository
{
    /**
     * @var ResourceModel\InvalidationLog
     */
    protected $resourceModel;

    /**
     * @var InvalidationLogFactory
     */
    protected $invalidationLogEntryFactory;

    public function __construct(
        InvalidationLogFactory $invalidationLogEntryFactory,
        \MageSuite\IndexInvalidationLogger\Model\ResourceModel\InvalidationLog $resourceModel
    )
    {
        $this->resourceModel = $resourceModel;
        $this->invalidationLogEntryFactory = $invalidationLogEntryFactory;
    }

    public function save($data)
    {
        /** @var InvalidationLog $invalidationLogEntry */
        $invalidationLogEntry = $this->invalidationLogEntryFactory->create();
        $invalidationLogEntry->setContext($data);

        $this->resourceModel->save($invalidationLogEntry);
    }
}
