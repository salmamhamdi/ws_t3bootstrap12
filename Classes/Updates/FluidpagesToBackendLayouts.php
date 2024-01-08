<?php

declare(strict_types=1);

namespace WapplerSystems\WsT3bootstrap\Updates;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Install\Updates\ConfirmableInterface;
use TYPO3\CMS\Install\Updates\Confirmation;
use TYPO3\CMS\Install\Updates\DatabaseUpdatedPrerequisite;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;


class FluidpagesToBackendLayouts implements UpgradeWizardInterface, ConfirmableInterface
{
    /**
     * @var Confirmation
     */
    protected $confirmation;

    public function __construct()
    {
        $this->confirmation = new Confirmation(
            'Please make sure to read the following carefully:',
            $this->getDescription(),
            false,
            'Yes, I understand!',
            '',
            true
        );
    }

    /**
     * @return string Unique identifier of this updater
     */
    public function getIdentifier(): string
    {
        return 'fluidpagesToBackendLayouts';
    }

    /**
     * @return string Title of this updater
     */
    public function getTitle(): string
    {
        return 'T3Bootstrap: Converting fluidpages settings to BackendLayouts';
    }

    /**
     * @return string Longer description of this updater
     */
    public function getDescription(): string
    {
        return 'Dies konvertiert alle tx_fed_page_controller_action und tx_fed_page_controller_action_sub '.
            'Datenbankeinträge in entsprechende BackendLayout-Einträge';
    }

    /**
     * Checks whether updates are required.
     *
     * @return bool Whether an update is required (TRUE) or not (FALSE)
     */
    public function updateNecessary(): bool
    {

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('pages');
        $queryBuilder->getRestrictions()->removeAll();
        $controllerActionCount = $queryBuilder->count('tx_fed_page_controller_action')
            ->from('pages')
            ->where(
                $queryBuilder->expr()->andX(
                    $queryBuilder->expr()->neq('tx_fed_page_controller_action', '""'),
                    $queryBuilder->expr()->isNotNull('tx_fed_page_controller_action'),
                    $queryBuilder->expr()->orX(
                        $queryBuilder->expr()->eq('backend_layout', '""'),
                        $queryBuilder->expr()->eq('backend_layout', '0')
                    )
                )
            )->execute()->fetchOne();

        $controllerActionSubCount = $queryBuilder->count('tx_fed_page_controller_action_sub')
            ->from('pages')
            ->where(
                $queryBuilder->expr()->andX(
                    $queryBuilder->expr()->neq('tx_fed_page_controller_action_sub', '""'),
                    $queryBuilder->expr()->isNotNull('tx_fed_page_controller_action_sub'),
                    $queryBuilder->expr()->orX(
                        $queryBuilder->expr()->eq('backend_layout_next_level', '""'),
                        $queryBuilder->expr()->eq('backend_layout_next_level', '0')
                    )
                )
            )->execute()->fetchOne();

        return $controllerActionCount > 0 || $controllerActionSubCount > 0;
    }

    /**
     * @return string[] All new fields and tables must exist
     */
    public function getPrerequisites(): array
    {
        return [
            DatabaseUpdatedPrerequisite::class,
        ];
    }

    /**
     * This upgrade wizard has informational character only, it does not perform actions.
     *
     * @return bool Whether everything went smoothly or not
     */
    public function executeUpdate(): bool
    {

        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('pages');
        $queryBuilder->getRestrictions()->removeAll();
        $statement = $queryBuilder->select('uid', 'tx_fed_page_controller_action')
            ->from('pages')
            ->where(
                $queryBuilder->expr()->andX(
                    $queryBuilder->expr()->neq('tx_fed_page_controller_action', '""'),
                    $queryBuilder->expr()->isNotNull('tx_fed_page_controller_action'),
                    $queryBuilder->expr()->orX(
                        $queryBuilder->expr()->eq('backend_layout', '""'),
                        $queryBuilder->expr()->eq('backend_layout', '0')
                    )
                )
            )->execute();

        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('pages');
        while ($row = $statement->fetch()) {
            $s = $row['tx_fed_page_controller_action'];
            $parts = explode('->', $s);

            $connection->update(
                'pages',
                [
                    'backend_layout' => 'pagets__' . ucfirst($parts[1]),
                    'tx_fed_page_controller_action' => ''
                ],
                ['uid' => (int)$row['uid']]
            );
        }


        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('pages');
        $queryBuilder->getRestrictions()->removeAll();
        $statement = $queryBuilder->select('uid', 'tx_fed_page_controller_action_sub')
            ->from('pages')
            ->where(
                $queryBuilder->expr()->andX(
                    $queryBuilder->expr()->neq('tx_fed_page_controller_action_sub', '""'),
                    $queryBuilder->expr()->isNotNull('tx_fed_page_controller_action_sub'),
                    $queryBuilder->expr()->orX(
                        $queryBuilder->expr()->eq('backend_layout_next_level', '""'),
                        $queryBuilder->expr()->eq('backend_layout_next_level', '0')
                    )
                )
            )->execute();

        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('pages');
        while ($row = $statement->fetch()) {
            $s = $row['tx_fed_page_controller_action_sub'];
            $parts = explode('->', $s);

            $connection->update(
                'pages',
                [
                    'backend_layout_next_level' => 'pagets__' . ucfirst($parts[1]),
                    'tx_fed_page_controller_action_sub' => ''
                ],
                ['uid' => (int)$row['uid']]
            );
        }


        return true;
    }

    /**
     * Return a confirmation message instance
     *
     * @return Confirmation
     */
    public function getConfirmation(): Confirmation
    {
        return $this->confirmation;
    }
}
