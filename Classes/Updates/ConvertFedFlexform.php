<?php

declare(strict_types=1);

namespace WapplerSystems\WsT3bootstrap\Updates;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Service\FlexFormService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Install\Updates\ConfirmableInterface;
use TYPO3\CMS\Install\Updates\Confirmation;
use TYPO3\CMS\Install\Updates\DatabaseUpdatedPrerequisite;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;


class ConvertFedFlexform implements UpgradeWizardInterface, ConfirmableInterface
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
        return 'convertFedFlexform';
    }

    /**
     * @return string Title of this updater
     */
    public function getTitle(): string
    {
        return 'T3Bootstrap: Converting flux flexform settings to page fields';
    }

    /**
     * @return string Longer description of this updater
     */
    public function getDescription(): string
    {
        return 'Dies konvertiert tx_fed_page_flexform Werte in pages Feld-Werte. Nur ausführen, wenn noch keine Werte gesetzt wurden. '.
            'Unterstützt noch nicht das Konvertieren der Hero-Bilder.';
    }

    /**
     * Checks whether updates are required.
     *
     * @return bool Whether an update is required (TRUE) or not (FALSE)
     */
    public function updateNecessary(): bool
    {

        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('pages');
        $queryBuilder->getRestrictions()->removeAll();
        $pagesCount = $queryBuilder->select('uid', 'tx_fed_page_flexform')
            ->from('pages')
            ->where(
                $queryBuilder->expr()->neq('tx_fed_page_flexform', '""')
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
        $statement = $queryBuilder->select('uid', 'tx_fed_page_flexform')
            ->from('pages')
            ->where(
                $queryBuilder->expr()->neq('tx_fed_page_flexform', '""')
            )
            ->execute();

        /** @var FlexFormService $flexFormService */
        $flexFormService = GeneralUtility::makeInstance(FlexFormService::class);

        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('pages');
        while ($row = $statement->fetch()) {
            $flexformData = $flexFormService->convertFlexFormContentToArray($row['tx_fed_page_flexform']);

            $headerimage = $flexformData['headerimage'] ?? '';
            $image = $flexformData['image'] ?? '';
            $heroslide = $flexformData['heroslide'] ?? -1;
            $jumbotron = $flexformData['jumbotron'] ?? 1;
            $heroContainer = $flexformData['heroContainer'] ?? 0;

            /*
            $connection->update(
                'pages',
                [
                    'heroslide' => $heroslide,
                    'heroContainer' => $heroContainer,
                    'jumbotron' => $jumbotron,
                ],
                ['uid' => (int)$row['uid']]
            );*/

            if ($headerimage !== '') {

                /*
                $resourceFactory = \TYPO3\CMS\Core\Resource\ResourceFactory::getInstance();
                $storage = $resourceFactory->getDefaultStorage();
                if ($storage) {
                    $newFile = $storage->addFile(
                        $headerimage,
                        $storage->getRootLevelFolder(),
                        'final_file_name.foo'
                    );
                }*/
            }
            if ($image !== '') {

                $resourceFactory = \TYPO3\CMS\Core\Resource\ResourceFactory::getInstance();
                $storage = $resourceFactory->getDefaultStorage();
                if ($storage) {
                    $file = $storage->getFile($image);

                }

            }

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
