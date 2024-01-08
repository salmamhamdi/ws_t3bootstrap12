<?php

declare(strict_types=1);

namespace WapplerSystems\WsT3bootstrap\Updates;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Install\Updates\ConfirmableInterface;
use TYPO3\CMS\Install\Updates\Confirmation;
use TYPO3\CMS\Install\Updates\DatabaseUpdatedPrerequisite;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;


class AddBackendLayoutNextLevelToRootPages implements UpgradeWizardInterface, ConfirmableInterface
{
    /**
     * @var Confirmation
     */
    protected $confirmation;

    public function __construct()
    {
        $this->confirmation = new Confirmation(
            'Are you sure?',
            'You should only execute this if you are using backend layouts. ',
            true
        );
    }

    /**
     * @return string Unique identifier of this updater
     */
    public function getIdentifier(): string
    {
        return 'addBackendLayoutNextLevelToRootPages';
    }

    /**
     * @return string Title of this updater
     */
    public function getTitle(): string
    {
        return 'T3Bootstrap: Adding a backend layout to root pages to avoid errors';
    }

    /**
     * @return string Longer description of this updater
     */
    public function getDescription(): string
    {
        return '';
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
        $pagesCount = $queryBuilder->count('uid')
            ->from('pages')
            ->where(
                $queryBuilder->expr()->andX(
                    $queryBuilder->expr()->eq('is_siteroot', '1'),
                    $queryBuilder->expr()->orX(
                        $queryBuilder->expr()->eq('backend_layout_next_level', '""'),
                        $queryBuilder->expr()->isNull('backend_layout_next_level')
                    )
                )
            )->execute()->fetchOne();

        return $pagesCount > 0;
    }

    /**
     * @return string[] All new fields and tables must exist
     */
    public function getPrerequisites(): array
    {
        return [
            DatabaseUpdatedPrerequisite::class,
            FluidpagesToBackendLayouts::class
        ];
    }

    /**
     * This upgrade wizard has informational character only, it does not perform actions.
     *
     * @return bool Whether everything went smoothly or not
     */
    public function executeUpdate(): bool
    {

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('pages');
        $queryBuilder->getRestrictions()->removeAll();
        $statement = $queryBuilder->select('uid')
            ->from('pages')
            ->where(
                $queryBuilder->expr()->andX(
                    $queryBuilder->expr()->eq('is_siteroot', '1'),
                    $queryBuilder->expr()->orX(
                        $queryBuilder->expr()->eq('backend_layout_next_level', '""'),
                        $queryBuilder->expr()->isNull('backend_layout_next_level')
                    )
                )
            )->execute();

        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('pages');
        while ($row = $statement->fetch()) {

            $connection->update(
                'pages',
                [
                    'backend_layout_next_level' => 'pagets__2Columns',
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
