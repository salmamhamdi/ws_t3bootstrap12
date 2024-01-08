<?php
namespace WapplerSystems\WsT3bootstrap\Hooks;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class that hooks into DataHandler and listens for updates to pages to update the
 * treelist cache
 * @internal this is a concrete TYPO3 hook implementation and solely used for EXT:frontend and not part of TYPO3's Core API.
 */
class MenuCacheUpdateHooks
{
    /**
     * Should not be manipulated from others except through the
     * configuration provided @see __construct()
     *
     * @var array
     */
    private $updateRequiringFields = [
        'pid',
    ];

    /**
     * Constructor, adds update requiring fields to the default ones
     */
    public function __construct()
    {
        // As enableFields can be set dynamically we add them here
        $pagesEnableFields = $GLOBALS['TCA']['pages']['ctrl']['enablecolumns'];
        foreach ($pagesEnableFields as $pagesEnableField) {
            $this->updateRequiringFields[] = $pagesEnableField;
        }
        $this->updateRequiringFields[] = $GLOBALS['TCA']['pages']['ctrl']['delete'];
        $this->updateRequiringFields[] = $GLOBALS['TCA']['pages']['ctrl']['sortby'];
        $this->updateRequiringFields[] = 'nav_hide';

    }

    /**
     * waits for DataHandler commands and looks for changed pages, if found further
     * changes take place to determine whether the cache needs to be updated
     *
     * @param string $status DataHandler operation status, either 'new' or 'update'
     * @param string $table The DB table the operation was carried out on
     * @param mixed $recordId The record's uid for update records, a string to look the record's uid up after it has been created
     * @param array $updatedFields Array of changed fields and their new values
     * @param DataHandler $dataHandler DataHandler parent object
     */
    public function processDatamap_afterDatabaseOperations($status, $table, $recordId, array $updatedFields, DataHandler $dataHandler)
    {
        if ($table === 'pages' && $this->requiresUpdate($updatedFields)) {
            $this->processClearCacheActions();
        }
    }

    /**
     * Waits for DataHandler commands and looks for deleted pages or swapped pages, if found
     * further changes take place to determine whether the cache needs to be updated
     *
     * @param string $command The TCE command
     * @param string $table The record's table
     * @param int $recordId The record's uid
     * @param array $commandValue The commands value, typically an array with more detailed command information
     * @param DataHandler $dataHandler The DataHandler parent object
     */
    public function processCmdmap_postProcess($command, $table, $recordId, $commandValue, DataHandler $dataHandler)
    {
        $action = (is_array($commandValue) && isset($commandValue['action'])) ? (string)$commandValue['action'] : '';
        if ($table === 'pages' && ($command === 'delete' || ($command === 'version' && $action === 'swap'))) {
            $this->processClearCacheActions();
        }
    }

    /**
     * waits for DataHandler commands and looks for moved pages, if found further
     * changes take place to determine whether the cache needs to be updated
     *
     * @param string $table Table name of the moved record
     * @param int $recordId The record's uid
     * @param int $destinationPid The record's destination page id
     * @param array $movedRecord The record that moved
     * @param array $updatedFields Array of changed fields
     * @param DataHandler $dataHandler DataHandler parent object
     */
    public function moveRecord_firstElementPostProcess($table, $recordId, $destinationPid, array $movedRecord, array $updatedFields, DataHandler $dataHandler)
    {
        if ($table === 'pages' && $this->requiresUpdate($updatedFields)) {
            $this->processClearCacheActions();
        }
    }

    /**
     * Waits for DataHandler commands and looks for moved pages, if found further
     * changes take place to determine whether the cache needs to be updated
     *
     * @param string $table Table name of the moved record
     * @param int $recordId The record's uid
     * @param int $destinationPid The record's destination page id
     * @param int $originalDestinationPid (negative) page id th page has been moved after
     * @param array $movedRecord The record that moved
     * @param array $updatedFields Array of changed fields
     * @param DataHandler $dataHandler DataHandler parent object
     */
    public function moveRecord_afterAnotherElementPostProcess($table, $recordId, $destinationPid, $originalDestinationPid, array $movedRecord, array $updatedFields, DataHandler $dataHandler)
    {
        if ($table === 'pages' && $this->requiresUpdate($updatedFields)) {
            $this->processClearCacheActions();
        }
    }

    /**
     * Checks whether the change requires an update of the treelist cache
     *
     * @param array $updatedFields Array of changed fields
     * @return bool TRUE if the treelist cache needs to be updated, FALSE if no update to the cache is required
     */
    protected function requiresUpdate(array $updatedFields)
    {
        $requiresUpdate = false;
        $updatedFieldNames = array_keys($updatedFields);
        foreach ($updatedFieldNames as $updatedFieldName) {
            if (in_array($updatedFieldName, $this->updateRequiringFields, true)) {
                $requiresUpdate = true;
                break;
            }
        }
        return $requiresUpdate;
    }

    /**
     *
     * @param int $affectedPage uid of the affected page
     * @param int $affectedParentPage parent uid of the affected page
     * @param array $updatedFields Array of updated fields and their new values
     * @param array $actions Array of actions to carry out
     */
    protected function processClearCacheActions()
    {
        /** @var CacheManager $cacheManager */
        $cacheManager = GeneralUtility::makeInstance(CacheManager::class);
        $cache = $cacheManager->getCache('wst3bootstrap_menu');
        $cache->flush();

    }



}
