<?php

namespace WapplerSystems\WsT3bootstrap\Backend\Form\FormDataProvider;


use TYPO3\CMS\Backend\Form\FormDataProviderInterface;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Page TsConfig relevant for this record
 */
class PageTsConfigOverrideChildTca implements FormDataProviderInterface
{
    /**
     * Add page TsConfig
     *
     * @param array $result
     * @return array
     */
    public function addData(array $result)
    {
        if (isset($result['pageTsConfig']['TCEFORM.'])) {

            $tables = $result['pageTsConfig']['TCEFORM.'];
            foreach ($tables as $tableName => $table) {

                if (is_array($table) && $result['tableName'] . '.' === $tableName) {
                    foreach ($table as $columnName => $column) {

                        if (isset($column['config.']['overrideChildTca.'])) {

                            $columnName = substr($columnName, 0, -1);

                            // addItems Part
                            if (isset($column['config.']['overrideChildTca.']['columns.']) && is_array($column['config.']['overrideChildTca.']['columns.'])) {

                                $fieldNames = $column['config.']['overrideChildTca.']['columns.'];
                                foreach ($fieldNames as $fieldName => $field) {
                                    if (isset($field['addItems.']) && is_array($field['addItems.'])) {

                                        $addItemsArray = $field['addItems.'];

                                        if (!isset($result['processedTca']['columns'][$columnName]['config']['overrideChildTca']['columns'][str_replace('.', '', $fieldName)]['config']['items']) || !is_array(isset($result['processedTca']['columns'][$columnName]['config']['overrideChildTca']['columns'][str_replace('.', '', $fieldName)]['config']['items']))) {
                                            $result['processedTca']['columns'][$columnName]['config']['overrideChildTca']['columns'][str_replace('.', '', $fieldName)]['config']['items'] = [];
                                        }

                                        foreach ($addItemsArray as $value => $label) {
                                            // If the value ends with a dot, it is a subelement like "34.icon = mylabel.png", skip it
                                            if (substr($value, -1) === '.') {
                                                continue;
                                            }
                                            // Check if value "34 = mylabel" also has a "34.icon = myImage.png"
                                            $iconIdentifier = null;
                                            if (isset($addItemsArray[$value . '.'])
                                                && is_array($addItemsArray[$value . '.'])
                                                && !empty($addItemsArray[$value . '.']['icon'])
                                            ) {
                                                $iconIdentifier = $addItemsArray[$value . '.']['icon'];
                                            }

                                            $result['processedTca']['columns'][$columnName]['config']['overrideChildTca']['columns'][str_replace('.', '', $fieldName)]['config']['items'][] = ['label' => $label, 'value' => $value, 'icon' => $iconIdentifier];
                                        }

                                    }

                                    unset($column['config.']['overrideChildTca.']['columns.'][$fieldName]['addItems.'], $result['pageTsConfig']['TCEFORM.'][$tableName][$columnName . '.']['config.']['overrideChildTca.']['columns.'][$fieldName]['addItems.']);
                                }

                                if (isset($result['processedTca']['columns'][$columnName]['config'])) {
                                    $config = GeneralUtility::removeDotsFromTS($column['config.']);
                                    ArrayUtility::mergeRecursiveWithOverrule($result['processedTca']['columns'][$columnName]['config'], $config);
                                }
                            }


                        }
                    }
                }


            }
        }

        return $result;
    }
}
