<?php

namespace MageSuite\IndexInvalidationLogger\Plugin\Framework\Indexer\Indexer;

class LogInvalidation
{
    const INVALIDATION = 'invalidation';
    const FULL_REINDEX = 'full_reindex';

    /**
     * @var \MageSuite\IndexInvalidationLogger\Helper\Configuration
     */
    protected $configuration;

    /**
     * @var \MageSuite\IndexInvalidationLogger\Model\Command\GenerateBasicLogData
     */
    protected $generateBasicLogData;

    /**
     * @var \MageSuite\IndexInvalidationLogger\Model\InvalidationLogRepository
     */
    protected $invalidationLogRepository;

    public function __construct(
        \MageSuite\IndexInvalidationLogger\Model\InvalidationLogRepository $invalidationLogRepository,
        \MageSuite\IndexInvalidationLogger\Helper\Configuration $configuration,
        \MageSuite\IndexInvalidationLogger\Model\Command\GenerateBasicLogData $generateBasicLogData
    )
    {
        $this->configuration = $configuration;
        $this->generateBasicLogData = $generateBasicLogData;
        $this->invalidationLogRepository = $invalidationLogRepository;
    }

    public function afterInvalidate(\Magento\Framework\Indexer\IndexerInterface $subject, $result)
    {
        if (!$this->configuration->isLoggingEnabled()) {
            return $result;
        }
        $stackTrace = $this->getStackTrace();

        $data = $this->generateBasicLogData->execute($stackTrace);
        $data['index'] = $subject->getId();
        $data['type'] = self::INVALIDATION;

        $this->invalidationLogRepository->save($data);

        return $result;
    }

    public function afterReindexAll(\Magento\Framework\Indexer\IndexerInterface $subject, $result)
    {
        if (!$this->configuration->isLoggingEnabled()) {
            return $result;
        }
        $stackTrace = $this->getStackTrace();

        $data = $this->generateBasicLogData->execute($stackTrace);
        $data['index'] = $subject->getId();
        $data['type'] = self::FULL_REINDEX;

        $this->invalidationLogRepository->save($data);

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
