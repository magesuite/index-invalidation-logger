<?php

namespace MageSuite\IndexInvalidationLogger\Controller\Adminhtml\Log;

class Index extends \Magento\Backend\App\Action
{
    public function execute()
    {
        $resultPage = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_PAGE);
        $resultPage->getConfig()->getTitle()->set(__('Index invalidation log'));

        return $resultPage;
    }
}
