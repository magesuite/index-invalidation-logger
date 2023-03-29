<?php

namespace MageSuite\IndexInvalidationLogger\Plugin\Smile\ElasticsuiteCore\Client\Client;

class LogUpdateAliases
{
    const UPDATE_ALIASES_ACTION_NAME = 'Update aliases';

    protected \MageSuite\IndexInvalidationLogger\Model\Command\GenerateBasicLogData $generateBasicLogData;

    protected \MageSuite\IndexInvalidationLogger\Model\InvalidationLogRepository $invalidationLogRepository;

    public function __construct(
        \MageSuite\IndexInvalidationLogger\Model\Command\GenerateBasicLogData $generateBasicLogData,
        \MageSuite\IndexInvalidationLogger\Model\InvalidationLogRepository $invalidationLogRepository
    ) {
        $this->generateBasicLogData = $generateBasicLogData;
        $this->invalidationLogRepository = $invalidationLogRepository;
    }

    public function afterUpdateAliases(
        \Smile\ElasticsuiteCore\Client\Client $indicesNamespace,
        $result,
        $params
    ) {
        $data = $this->generateBasicLogData->execute($this->getStackTrace());
        $data['index'] = json_encode($params);
        $data['type'] = self::UPDATE_ALIASES_ACTION_NAME;

        $this->invalidationLogRepository->save($data);
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
