<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

ExtensionManagementUtility::addToAllTCAtypes('tt_content', '--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.footers;footers,', 'wst3bootstrap_card', 'after:header');
