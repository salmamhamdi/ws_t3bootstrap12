<?php

declare(strict_types=1);

namespace WapplerSystems\WsT3bootstrap\Updates;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Install\Updates\ConfirmableInterface;
use TYPO3\CMS\Install\Updates\Confirmation;
use TYPO3\CMS\Install\Updates\DatabaseUpdatedPrerequisite;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;


class ConvertFluidcontent implements UpgradeWizardInterface, ConfirmableInterface
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
        return 'convertFluidcontent';
    }

    /**
     * @return string Title of this updater
     */
    public function getTitle(): string
    {
        return 'T3Bootstrap: Converting old fluidcontent_xxxxx CType';
    }

    /**
     * @return string Longer description of this updater
     */
    public function getDescription(): string
    {
        return 'Dies konvertiert alte fluidcontent_xxxxx Elemente in neuere CTypes';
    }

    /**
     * Checks whether updates are required.
     *
     * @return bool Whether an update is required (TRUE) or not (FALSE)
     */
    public function updateNecessary(): bool
    {

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tt_content');
        $queryBuilder->getRestrictions()->removeAll();
        $elementCount = $queryBuilder->count('uid')
            ->from('tt_content')
            ->where($queryBuilder->expr()->andX(
                $queryBuilder->expr()->like('CType', $queryBuilder->createNamedParameter('fluidcontent_%', \PDO::PARAM_STR)),
                $queryBuilder->expr()->neq('CType', $queryBuilder->createNamedParameter('fluidcontent_content', \PDO::PARAM_STR))
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
            'fluidcontent_fluidrow' => 'wst3bootstrap_fluidrow',
            'fluidcontent_buttongroup' => 'wst3bootstrap_buttongroup',
            'fluidcontent_accordion' => 'wst3bootstrap_accordion',
            'fluidcontent_alert' => 'wst3bootstrap_alert',
            'fluidcontent_buttonlink' => 'wst3bootstrap_buttonlink',
            'fluidcontent_card' => 'wst3bootstrap_card',
            'fluidcontent_carousel' => 'wst3bootstrap_carousel',
            'fluidcontent_container' => 'wst3bootstrap_container',
            'fluidcontent_example' => 'wst3bootstrap_example',
            'fluidcontent_googlemap' => 'wst3bootstrap_googlemap',
            'fluidcontent_megamenu' => 'wst3bootstrap_megamenu',
            'fluidcontent_panel' => 'wst3bootstrap_panel',
            'fluidcontent_tabs' => 'wst3bootstrap_tabs',
            'fluidcontent_thumbnail' => 'wst3bootstrap_thumbnail',
            'fluidcontent_well' => 'wst3bootstrap_well',
            'fluidcontent_media' => 'wst3bootstrap_media',
        ];

        foreach ($mapping as $oldCType => $cType) {

            $queryBuilder = $connectionPool->getQueryBuilderForTable('tt_content');
            $queryBuilder->getRestrictions()->removeAll();
            $queryBuilder
                ->update('tt_content')
                ->where(
                    $queryBuilder->expr()->eq('CType', $queryBuilder->createNamedParameter($oldCType))
                )
                ->set('CType', $cType)
                ->execute();
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
