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


class ConvertFluidcontentContent implements UpgradeWizardInterface, ConfirmableInterface
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
        return 'convertFluidcontentContent';
    }

    /**
     * @return string Title of this updater
     */
    public function getTitle(): string
    {
        return 'T3Bootstrap: Converting old fluidcontent_content CType';
    }

    /**
     * @return string Longer description of this updater
     */
    public function getDescription(): string
    {
        return 'Dies konvertiert alte fluidcontent_content Elemente in neuere CTypes';
    }

    /**
     * Checks whether updates are required.
     *
     * @return bool Whether an update is required (TRUE) or not (FALSE)
     */
    public function updateNecessary(): bool
    {

        $schemaManager = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('tt_content')->getSchemaManager();
        $columns = $schemaManager->listTableColumns('tt_content');

        if (!array_key_exists('tx_fed_fcefile',$columns)) return false;

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tt_content');
        $queryBuilder->getRestrictions()->removeAll();
        $elementCount = $queryBuilder->count('uid')
            ->from('tt_content')
            ->where($queryBuilder->expr()->eq('CType', $queryBuilder->createNamedParameter('fluidcontent_content', \PDO::PARAM_STR)))->execute()->fetchOne();

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
            'ws_t3bootstrap:FluidRow.html' => 'wst3bootstrap_fluidrow',
            'ws_t3bootstrap:ButtonGroup.html' => 'wst3bootstrap_buttongroup',
            'ws_t3bootstrap:Accordion.html' => 'wst3bootstrap_accordion',
            'ws_t3bootstrap:Alert.html' => 'wst3bootstrap_alert',
            'ws_t3bootstrap:ButtonLink.html' => 'wst3bootstrap_buttonlink',
            'ws_t3bootstrap:Card.html' => 'wst3bootstrap_card',
            'ws_t3bootstrap:Carousel.html' => 'wst3bootstrap_carousel',
            'ws_t3bootstrap:Container.html' => 'wst3bootstrap_container',
            'ws_t3bootstrap:Example.html' => 'wst3bootstrap_example',
            'ws_t3bootstrap:GoogleMap.html' => 'wst3bootstrap_googlemap',
            'ws_t3bootstrap:Megamenu.html' => 'wst3bootstrap_megamenu',
            'ws_t3bootstrap:Panel.html' => 'wst3bootstrap_panel',
            'ws_t3bootstrap:Tabs.html' => 'wst3bootstrap_tabs',
            'ws_t3bootstrap:Thumbnail.html' => 'wst3bootstrap_thumbnail',
            'ws_t3bootstrap:Well.html' => 'wst3bootstrap_well',
            'ws_t3bootstrap:Media.html' => 'wst3bootstrap_media',
        ];

        foreach ($mapping as $tx_fed_fcefile => $ctype) {

            /** @var QueryBuilder $queryBuilder */
            $queryBuilder = $connectionPool->getQueryBuilderForTable('tt_content');
            $queryBuilder->getRestrictions()->removeAll();
            $queryBuilder
                ->update('tt_content')
                ->where(
                    $queryBuilder->expr()->andX(
                        $queryBuilder->expr()->eq('CType', $queryBuilder->createNamedParameter('fluidcontent_content')),
                        $queryBuilder->expr()->eq('tx_fed_fcefile', $queryBuilder->createNamedParameter($tx_fed_fcefile))
                    )
                )
                ->set('CType', $ctype)
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
