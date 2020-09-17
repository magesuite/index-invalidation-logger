<?php

namespace MageSuite\IndexInvalidationLogger\Helper;

class Configuration
{
    const XML_PATH_INDEX_INVALIDATION_DEBUGGER_CONFIGURATION = 'system/index_invalidation_debugger';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    protected $config = null;

    public function __construct(\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfigInterface)
    {
        $this->scopeConfig = $scopeConfigInterface;
    }

    public function isLoggingEnabled()
    {
        return $this->getConfig()->getIsLoggingEnabled();
    }

    public function getLoggingRetentionPeriod()
    {
        return $this->getConfig()->getLoggingRetentionPeriod();
    }

    protected function getConfig()
    {
        if ($this->config === null) {
            $config = $this->scopeConfig->getValue(self::XML_PATH_INDEX_INVALIDATION_DEBUGGER_CONFIGURATION);

            if (!is_array($config) || $config === null) {
                $config = [];
            }

            $this->config = new \Magento\Framework\DataObject($config);
        }

        return $this->config;
    }
}
