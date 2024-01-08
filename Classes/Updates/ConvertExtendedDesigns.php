<?php

declare(strict_types=1);

namespace WapplerSystems\WsT3bootstrap\Updates;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Install\Updates\DatabaseUpdatedPrerequisite;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;


class ConvertExtendedDesigns implements UpgradeWizardInterface
{


    public function __construct()
    {

    }

    /**
     * @return string Unique identifier of this updater
     */
    public function getIdentifier(): string
    {
        return 'convertExtendedDesigns';
    }

    /**
     * @return string Title of this updater
     */
    public function getTitle(): string
    {
        return 'T3Bootstrap: Converting "extended_design" ';
    }

    /**
     * @return string Longer description of this updater
     */
    public function getDescription(): string
    {
        return 'Converts the "extended_design" field values from number to name';
    }

    /**
     * Checks whether updates are required.
     *
     * @return bool Whether an update is required (TRUE) or not (FALSE)
     */
    public function updateNecessary(): bool
    {

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('sys_file_reference');
        $queryBuilder->getRestrictions()->removeAll();
        $elementCount = $queryBuilder->count('uid')
            ->from('sys_file_reference')
            ->where($queryBuilder->expr()->orX(
                $queryBuilder->expr()->eq('extended_design', $queryBuilder->createNamedParameter('0', \PDO::PARAM_INT)),
                $queryBuilder->expr()->eq('extended_design', $queryBuilder->createNamedParameter('1', \PDO::PARAM_INT)),
                $queryBuilder->expr()->eq('extended_design', $queryBuilder->createNamedParameter('2', \PDO::PARAM_INT)),
                $queryBuilder->expr()->eq('extended_design', $queryBuilder->createNamedParameter('3', \PDO::PARAM_INT))
            ))
            ->execute()->fetchOne();

        return $elementCount > 0;
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

        $mapping = [
            '0' => '',
            '1' => 'lily',
            '2' => 'sadie',
            '3' => 'honey',
        ];

        foreach ($mapping as $oldValue => $newValue) {

            $queryBuilder = $connectionPool->getQueryBuilderForTable('sys_file_reference');
            $queryBuilder->getRestrictions()->removeAll();
            $queryBuilder
                ->update('sys_file_reference')
                ->where(
                    $queryBuilder->expr()->eq('extended_design', $queryBuilder->createNamedParameter($oldValue))
                )
                ->set('extended_design', $newValue)
                ->execute();
        }

        return true;
    }


}
