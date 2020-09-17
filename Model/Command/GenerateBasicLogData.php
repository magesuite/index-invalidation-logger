<?php

namespace MageSuite\IndexInvalidationLogger\Model\Command;

class GenerateBasicLogData
{
    /**
     * @var \Magento\Backend\Model\Auth\Session
     */
    protected $authSession;

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $url;

    public function __construct(
        \Magento\Backend\Model\Auth\Session $authSession,
        \Magento\Framework\UrlInterface $url
    ) {
        $this->authSession = $authSession;
        $this->url = $url;
    }

    public function execute($stackTrace)
    {
        $stackTrace = $this->getStackTraceAsString($stackTrace);

        $data = [
            'stack_trace' => $stackTrace
        ];

        if (PHP_SAPI === 'cli') {
            $data['cli'] = true;
            $data['command'] = isset($_SERVER['argv']) ? implode(' ', $_SERVER['argv']) : '';
        } else {
            $data['url'] = $this->url->getCurrentUrl();
        }

        if ($this->authSession->isLoggedIn() && $this->authSession->getUser()) {
            $data['admin_user'] = $this->authSession->getUser()->getUserName();
        }

        return $data;
    }

    /**
     * getTraceAsString is not used because it returns arguments passed to methods. Arguments are dynamic and would
     * cause multiple stacktrace duplicates that differs only by method arguments.
     * @param $stackTrace
     * @return string
     */
    protected function getStackTraceAsString($stackTrace)
    {
        $output = '';

        $iterator = 1;

        foreach ($stackTrace as $traceItem) {
            $method = '';

            if (isset($traceItem['file'])) {
                $method .= $traceItem['file'];
            }

            if (isset($traceItem['line'])) {
                $method .= '(' . $traceItem['line'] . ')';
            }

            $method .= ': ';

            if (isset($traceItem['class'])) {
                $method .= $traceItem['class'];
            }

            if (isset($traceItem['type'])) {
                $method .= $traceItem['type'];
            }

            if (isset($traceItem['function'])) {
                $method .= $traceItem['function'].'()';
            }

            $output .= '#' . $iterator . ': ' . $method . PHP_EOL;

            $iterator++;
        }

        return $output;
    }
}
