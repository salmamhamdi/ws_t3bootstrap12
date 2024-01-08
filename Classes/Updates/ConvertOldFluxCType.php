<?php

declare(strict_types=1);

namespace WapplerSystems\WsT3bootstrap\Updates;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Install\Updates\ConfirmableInterface;
use TYPO3\CMS\Install\Updates\Confirmation;
use TYPO3\CMS\Install\Updates\DatabaseUpdatedPrerequisite;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;


class ConvertOldFluxCType implements UpgradeWizardInterface, ConfirmableInterface
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
        return 'convertOldFluxCType';
    }

    /**
     * @return string Title of this updater
     */
    public function getTitle(): string
    {
        return 'T3Bootstrap: Converting old flux_xxxx CTypes';
    }

    /**
     * @return string Longer description of this updater
     */
    public function getDescription(): string
    {
        return 'Dies konvertiert alte flux_xxxxx Elemente in neuere CTypes. Danach müssen Sie das Aktualisierungs-Skript im Extension-Manager der flux Extension ausführen.';
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
            ->where($queryBuilder->expr()->like('CType', $queryBuilder->createNamedParameter('flux_%', \PDO::PARAM_STR)))
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
            'flux_fluidrow' => 'wst3bootstrap_fluidrow',
            'flux_buttongroup' => 'wst3bootstrap_buttongroup',
            'flux_accordion' => 'wst3bootstrap_accordion',
            'flux_alert' => 'wst3bootstrap_alert',
            'flux_buttonlink' => 'wst3bootstrap_buttonlink',
            'flux_card' => 'wst3bootstrap_card',
            'flux_carousel' => 'wst3bootstrap_carousel',
            'flux_container' => 'wst3bootstrap_container',
            'flux_example' => 'wst3bootstrap_example',
            'flux_googlemap' => 'wst3bootstrap_googlemap',
            'flux_megamenu' => 'wst3bootstrap_megamenu',
            'flux_panel' => 'wst3bootstrap_panel',
            'flux_tabs' => 'wst3bootstrap_tabs',
            'flux_thumbnail' => 'wst3bootstrap_thumbnail',
            'flux_well' => 'wst3bootstrap_well',
            'flux_media' => 'wst3bootstrap_media',
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
