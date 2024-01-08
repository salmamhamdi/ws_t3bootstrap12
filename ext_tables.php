<?php

use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;
use TYPO3\CMS\Core\Imaging\IconRegistry;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;


ExtensionManagementUtility::allowTableOnStandardPages('tx_wst3bootstrap_card_element');
ExtensionManagementUtility::allowTableOnStandardPages('tx_wst3bootstrap_counterbar_item');


/* Context help */
ExtensionManagementUtility::addLLrefForTCAdescr('tt_content', 'EXT:ws_t3bootstrap/Resources/Private/Language/locallang_csh_ttcontent.xlf');

/* add backend css */
$GLOBALS['TBE_STYLES']['skins']['backend']['stylesheetDirectories']['ws_t3bootstrap'] = 'EXT:ws_t3bootstrap/Resources/Public/CSS/Backend/';

