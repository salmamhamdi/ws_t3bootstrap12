<?php
declare(strict_types = 1);

namespace WapplerSystems\WsT3bootstrap\Frontend\ContentObject\Exception;


use TYPO3\CMS\Core\Crypto\Random;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Http\ImmediateResponseException;
use TYPO3\CMS\Core\SysLog\Action as SystemLogGenericAction;
use TYPO3\CMS\Core\SysLog\Error as SystemLogErrorClassification;
use TYPO3\CMS\Core\SysLog\Type as SystemLogType;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Frontend\ContentObject\AbstractContentObject;

class ProductionExceptionHandler extends \TYPO3\CMS\Frontend\ContentObject\Exception\ProductionExceptionHandler
{

    /**
     * Handles exceptions thrown during rendering of content objects
     * The handler can decide whether to re-throw the exception or
     * return a nice error message for production context.
     *
     * @param \Exception $exception
     * @param AbstractContentObject $contentObject
     * @param array $contentObjectConfiguration
     * @return string
     * @throws \Exception
     */
    public function handle(\Exception $exception, AbstractContentObject $contentObject = null, $contentObjectConfiguration = []): string
    {
        // ImmediateResponseException should work similar to exit / die and must therefore not be handled by this ExceptionHandler.
        if ($exception instanceof ImmediateResponseException) {
            throw $exception;
        }

        if (!empty($this->configuration['ignoreCodes.'])) {
            if (in_array($exception->getCode(), array_map('intval', $this->configuration['ignoreCodes.']), true)) {
                throw $exception;
            }
        }


        $errorMessage = $this->configuration['errorMessage'] ?? 'Oops, an error occurred! Code: %s';

        $code = date('YmdHis', $_SERVER['REQUEST_TIME']) . GeneralUtility::makeInstance(Random::class)->generateRandomHexString(8);

        $this->logException($exception, $errorMessage, $code);

        $html = '<div class="alert alert-danger">%s</div>';

        return sprintf($html,LocalizationUtility::translate('error_message','ws_t3bootstrap'));
    }


    /**
     * @param \Exception $exception
     * @param string $errorMessage
     * @param string $code
     */
    protected function logException(\Exception $exception, string $errorMessage, string $code): void
    {
        $this->logger->alert(sprintf($errorMessage, $code), ['exception' => $exception]);
        $this->writeLog(sprintf($errorMessage, $code).', uri: '.$_SERVER['REQUEST_URI']);
    }

    /**
     * Writes an exception in the sys_log table
     *
     * @param string $logMessage Default text that follows the message.
     */
    protected function writeLog(string $logMessage, $data = []): void
    {
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('sys_log');

        if (!$connection->isConnected()) {
            return;
        }
        $userId = 0;
        $workspace = 0;
        $backendUser = $this->getBackendUser();
        if (is_object($backendUser)) {
            if (isset($backendUser->user['uid'])) {
                $userId = $backendUser->user['uid'];
            }
            if (isset($backendUser->workspace)) {
                $workspace = $backendUser->workspace;
            }
            if (!empty($backendUser->user['ses_backuserid'])) {
                $data['originalUser'] = $backendUser->user['ses_backuserid'];
            }
        }

        $connection->insert(
            'sys_log',
            [
                'userid' => $userId,
                'type' => SystemLogType::ERROR,
                'action' => SystemLogGenericAction::UNDEFINED,
                'error' => SystemLogErrorClassification::SYSTEM_ERROR,
                'details_nr' => 0,
                'details' => str_replace('%', '%%', $logMessage),
                'log_data' => empty($data) ? '' : serialize($data),
                'IP' => (string)GeneralUtility::getIndpEnv('REMOTE_ADDR'),
                'tstamp' => $GLOBALS['EXEC_TIME'],
                'workspace' => $workspace
            ]
        );
    }


    /**
     * @return \TYPO3\CMS\Core\Authentication\BackendUserAuthentication
     */
    protected function getBackendUser()
    {
        return $GLOBALS['BE_USER'];
    }

}
