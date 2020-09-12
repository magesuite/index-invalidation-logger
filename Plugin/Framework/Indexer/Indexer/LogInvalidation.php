<?php

namespace MageSuite\IndexInvalidationLogger\Plugin\Framework\Indexer\Indexer;

class LogInvalidation
{
    public function __construct(
        \MageSuite\IndexInvalidationLogger\Model\InvalidationLogRepository $cleanupLogRepository,
        \MageSuite\IndexInvalidationLogger\Helper\Configuration $configuration,
        \MageSuite\IndexInvalidationLogger\Model\Command\GenerateBasicLogData $generateBasicCleanupLogData
    )
    {
        $this->configuration = $configuration;
        $this->generateBasicCleanupLogData = $generateBasicCleanupLogData;
        $this->cleanupLogRepository = $cleanupLogRepository;
    }

    public function afterInvalidate(\Magento\Framework\Indexer\IndexerInterface $subject, $result)
    {
        if (!$this->configuration->isLoggingEnabled()) {
            return $result;
        }
        $stackTrace = $this->getStackTrace();

        $data = $this->generateBasicCleanupLogData->execute($stackTrace);
        $data['index'] = $subject->getId();

        $this->cleanupLogRepository->save($data);

        return $result;
    }

    protected function getStackTrace()
    {
        try {
            throw new \Exception();
        } catch (\Exception $e) {
            return $e->getTrace();
        }
    }
}
