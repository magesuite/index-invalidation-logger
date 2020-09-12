<?php

namespace MageSuite\IndexInvalidationLogger\Model\ResourceModel;

class InvalidationLog extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * @var InvalidationLogStacktrace
     */
    protected $stacktraceResourceModel;

    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        $connectionName = null,
        InvalidationLogStacktrace $stacktraceResourceModel
    )
    {
        parent::__construct($context, $connectionName);

        $this->stacktraceResourceModel = $stacktraceResourceModel;
    }

    protected function _construct()
    {
        $this->_init('index_invalidation_log', 'id');
    }

    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {
        $context = $object->getContext();
        $stackTrace = $context['stack_trace'];

        $stackTraceId = $this->stacktraceResourceModel->getStackTraceId($stackTrace);

        unset($context['stack_trace']);

        $object->setContext(json_encode($context));
        $object->setStacktraceId($stackTraceId);

        return parent::_beforeSave($object);
    }
}
