<?php

namespace MageSuite\IndexInvalidationLogger\Model\ResourceModel;

class InvalidationLogStacktrace extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected $existingStackTracesIdsCache = [];

    protected function _construct()
    {
        $this->_init('index_invalidation_log_stacktrace', 'id');
    }

    public function getStackTraceId($stackTrace)
    {
        $hash = hash('md5', $stackTrace);

        if (isset($this->existingStackTracesIdsCache[$hash]) && $this->existingStackTracesIdsCache[$hash] > 0) {
            return $this->existingStackTracesIdsCache[$hash];
        }

        $stacktraceId = $this->getExistingStacktraceId($hash);

        if ($stacktraceId == null) {
            $this->getConnection()->insert(
                $this->getMainTable(),
                [
                    'stacktrace' => $stackTrace,
                    'hash' => $hash
                ]
            );

            $stacktraceId = $this->getConnection()->lastInsertId();
        }

        $this->existingStackTracesIdsCache[$hash] = $stacktraceId;

        return $this->existingStackTracesIdsCache[$hash];
    }

    protected function getExistingStacktraceId(string $hash)
    {
        $select = $this->getConnection()->select();
        $select->from($this->getMainTable(), 'id');
        $select->where('hash = ?', $hash);

        $row = $this->getConnection()->fetchRow($select);

        return $row === null ? null : $row['id'];
    }
}
