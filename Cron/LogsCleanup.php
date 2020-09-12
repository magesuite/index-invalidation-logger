<?php

namespace MageSuite\IndexInvalidationLogger\Cron;

class LogsCleanup
{
    /**
     * @var \MageSuite\IndexInvalidationLogger\Helper\Configuration
     */
    protected $configuration;

    /**
     * @var \MageSuite\IndexInvalidationLogger\Model\Command\CleanLogs
     */
    protected $cleanLogs;

    public function __construct(
        \MageSuite\IndexInvalidationLogger\Helper\Configuration $configuration,
        \MageSuite\IndexInvalidationLogger\Model\Command\CleanLogs $cleanLogs
    )
    {
        $this->configuration = $configuration;
        $this->cleanLogs = $cleanLogs;
    }

    public function execute()
    {
        if (!$this->configuration->isLoggingEnabled()) {
            return;
        }

        $this->cleanLogs->execute($this->configuration->getLoggingRetentionPeriod());
    }
}
