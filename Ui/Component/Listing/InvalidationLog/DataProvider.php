<?php

namespace MageSuite\IndexInvalidationLogger\Ui\Component\Listing\InvalidationLog;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        \MageSuite\IndexInvalidationLogger\Model\ResourceModel\InvalidationLog\CollectionFactory $invalidationLogCollectionFactory,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $invalidationLogCollectionFactory->create();

        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }
}
