<?php

declare(strict_types=1);

namespace WapplerSystems\WsT3bootstrap\Updates;

use FluidTYPO3\Flux\Utility\ColumnNumberUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Install\Updates\ConfirmableInterface;
use TYPO3\CMS\Install\Updates\Confirmation;
use TYPO3\CMS\Install\Updates\DatabaseUpdatedPrerequisite;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;


class RemoveFluxColumnOffset implements UpgradeWizardInterface, ConfirmableInterface
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
            'No, thanks',
            false
        );
    }

    /**
     * @return string Unique identifier of this updater
     */
    public function getIdentifier(): string
    {
        return 'removeFluxColumnOffset';
    }

    /**
     * @return string Title of this updater
     */
    public function getTitle(): string
    {
        return 'T3Bootstrap: Removing flux column offset';
    }

    /**
     * @return string Longer description of this updater
     */
    public function getDescription(): string
    {
        return 'It corrects the offset of the flux column numbers, which was generated before ws_t3bootstrap version 11.2.8 still in the template with math.sum for old compatibility reasons. Execute it only when necessary!';
    }

    /**
     * Checks whether updates are required.
     *
     * @return bool Whether an update is required (TRUE) or not (FALSE)
     */
    public function updateNecessary(): bool
    {

        return true;
    }

    /**
     * @return string[] All new fields and tables must exist
     */
    public function getPrerequisites(): array
    {
        return [
            DatabaseUpdatedPrerequisite::class,
            ConvertFluidcontent::class,
            ConvertFluidcontentContent::class
        ];
    }

    /**
     * This upgrade wizard has informational character only, it does not perform actions.
     *
     * @return bool Whether everything went smoothly or not
     */
    public function executeUpdate(): bool
    {

        $ctypesAndOffsets = [
            'wst3bootstrap_accordion' => 20,
            'wst3bootstrap_alert' => 21,
            'wst3bootstrap_buttongroup' => 22,
            'wst3bootstrap_card' => 23,
            'wst3bootstrap_cards' => 1,
            'wst3bootstrap_carousel' => 51,
            'wst3bootstrap_container' => 25,
            'wst3bootstrap_example' => 26,
            'wst3bootstrap_fluidrow' => 28,
            'wst3bootstrap_media' => 1,
            'wst3bootstrap_megamenu' => 29,
            'wst3bootstrap_panel' => 1,
            'wst3bootstrap_tabs' => 30,
            'wst3bootstrap_thumbnail' => 31,
            'wst3bootstrap_well' => 32,
        ];


        foreach ($ctypesAndOffsets as $cType => $offset) {
            $this->migrateCType($cType,$offset);
        }

        return true;
    }


    private function migrateCType($cType, $offset) {

        /** @var ConnectionPool $connectionPool */
        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);

        $queryBuilder = $connectionPool->getQueryBuilderForTable('tt_content');
        $queryBuilder->getRestrictions()->removeAll();
        $statement = $queryBuilder->select('uid','pid')
            ->from('tt_content')
            ->where($queryBuilder->expr()->andX(
                $queryBuilder->expr()->eq('CType',$queryBuilder->createNamedParameter($cType, \PDO::PARAM_STR))
            ))
            ->execute();

        while ($row = $statement->fetchAssociative()) {

            $childrenColPosRange = ColumnNumberUtility::calculateMinimumAndMaximumColumnNumberWithinParent($row['uid']);

            $queryBuilder = $connectionPool->getQueryBuilderForTable('tt_content');
            $queryBuilder->getRestrictions()->removeAll();
            $statementChildren = $queryBuilder->select('uid','colPos','pid')
                ->from('tt_content')
                ->where($queryBuilder->expr()->andX(
                    $queryBuilder->expr()->gte('colPos',$childrenColPosRange[0]),
                    $queryBuilder->expr()->lte('colPos',$childrenColPosRange[1]),
                    $queryBuilder->expr()->eq('tx_wst3bootstrap_migrated_version',$queryBuilder->createNamedParameter('', \PDO::PARAM_STR)),
                ))
                ->execute();

            while ($rowChild = $statementChildren->fetchAssociative()) {

                $queryBuilder = $connectionPool->getQueryBuilderForTable('tt_content');
                $queryBuilder->getRestrictions()->removeAll();
                $queryBuilder
                    ->update('tt_content')
                    ->where(
                        $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($rowChild['uid'], \PDO::PARAM_INT))
                    )
                    ->set('colPos', $rowChild['colPos']-$offset)
                    ->set('tx_wst3bootstrap_migrated_version','11.2.8')
                    ->execute();

            }

        }

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
