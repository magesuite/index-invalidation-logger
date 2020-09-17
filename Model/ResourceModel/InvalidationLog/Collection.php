<?php

namespace MageSuite\IndexInvalidationLogger\Model\ResourceModel\InvalidationLog;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'id';

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init(
            \MageSuite\IndexInvalidationLogger\Model\InvalidationLog::class,
            \MageSuite\IndexInvalidationLogger\Model\ResourceModel\InvalidationLog::class
        );
    }

    public function _beforeLoad()
    {
        $this->join(
            ['stacktrace' => 'index_invalidation_log_stacktrace'],
            'main_table.stacktrace_id=stacktrace.id',
            ['stacktrace', 'hash']
        );

        return parent::_beforeLoad();
    }

    public function _afterLoad()
    {
        foreach ($this->getItems() as $item) {
            $this->buildItem($item);
        }

        return parent::_afterLoad();
    }

    public function addFieldToFilter($field, $condition = null)
    {
        if ($field == 'fulltext') {
            return parent::addFieldToFilter(
                'context',
                ['like' => '%' . $condition['fulltext'] . '%']
            );
        }

        return parent::addFieldToFilter($field, $condition);
    }

    protected function getExtra($context)
    {
        $output = '';

        if (isset($context['url'])) {
            $output .= 'URL: ' . $context['url'] . '<br>';
        }

        if (isset($context['cli']) && $context['cli']) {
            $output .= 'CLI: ' . $context['command'] . '<br>';
        }

        if (isset($context['admin_user'])) {
            $output .= 'Admin user: ' . $context['admin_user'] . '<br>';
        }

        return $output;
    }

    protected function getStackTrace($id, $stackTrace, $hash)
    {
        $htmlElementId = $hash . '_' . $id;

        $stackTrace = nl2br($stackTrace);

        return <<<HTML
                <a onclick="javascript: openStacktrace('$htmlElementId')">Show stacktrace</a>
                <div id="stacktrace-modal-{$htmlElementId}" style="display:none;">
                    {$stackTrace}
                </div>

HTML;
    }

    protected function buildItem(\Magento\Framework\DataObject $item)
    {
        $context = json_decode($item->getContext(), true);

        $stackTrace = $this->getStackTrace(
            $item->getData('id'),
            $item->getData('stacktrace'),
            $item->getData('hash')
        );

        $item->setIndex($context['index']);
        $item->setExtra($this->getExtra($context));
        $item->setStackTrace($stackTrace);
    }
}
