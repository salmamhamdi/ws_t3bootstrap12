<?php

declare(strict_types=1);

namespace WapplerSystems\WsT3bootstrap\Updates;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Install\Updates\ConfirmableInterface;
use TYPO3\CMS\Install\Updates\Confirmation;
use TYPO3\CMS\Install\Updates\DatabaseUpdatedPrerequisite;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;


class ConvertHeaderSize implements UpgradeWizardInterface, ConfirmableInterface
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
        return 'convertHeadersize';
    }

    /**
     * @return string Title of this updater
     */
    public function getTitle(): string
    {
        return 'T3Bootstrap: Converting the "header_big" field to the new "header_size" field.';
    }

    /**
     * @return string Longer description of this updater
     */
    public function getDescription(): string
    {
        return 'It converts the "header_big" field to the new "header_size" field. Remove column after running.';
    }

    /**
     * Checks whether updates are required.
     *
     * @return bool Whether an update is required (TRUE) or not (FALSE)
     */
    public function updateNecessary(): bool
    {

        $schemaManager = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('pages')->getSchemaManager();
        $columns = $schemaManager->listTableColumns('pages');

        if (array_key_exists('header_big',$columns)) return true;

        return false;
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
        /** @var ConnectionPool $connectionPool */
        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);

        $queryBuilder = $connectionPool->getQueryBuilderForTable('pages');
        $queryBuilder->getRestrictions()->removeAll();
        $queryBuilder
            ->update('pages')
            ->where(
                $queryBuilder->expr()->eq('header_big', 1)
            )
            ->set('header_size', 'big')
            ->execute();

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
