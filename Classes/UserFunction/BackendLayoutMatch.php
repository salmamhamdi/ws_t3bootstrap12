<?php

namespace WapplerSystems\WsT3bootstrap\UserFunction;

use TYPO3\CMS\Backend\Utility\BackendUtility;

class BackendLayoutMatch
{

    public function check($parameters,$evaluateDisplayConditions) {

        $record = $parameters['record'] ?? null;
        if ($record === null) {
            return false;
        }
        $conditionParameters = $parameters['conditionParameters'] ?? [];

        $neededBackendLayouts = explode(',',$conditionParameters[0] ?? '');
        $pageRecord = BackendUtility::getRecord('pages',$record['pid']);
        if ($pageRecord === null) {
            return false;
        }
        $beLayout = $pageRecord['backend_layout'] ?? '';

        if (in_array($beLayout, $neededBackendLayouts, true)) return true;

        if ($beLayout === '') {
            $rootline = BackendUtility::BEgetRootLine($pageRecord['pid']);
            foreach ($rootline as $pageRecord) {
                $layout = $pageRecord['backend_layout_next_level'] ?? '';
                if ($layout !== '') {
                    return in_array($layout, $neededBackendLayouts, true);
                }
                if ($pageRecord['is_siteroot'] === 1) {
                    break;
                }
            }
        }
        return false;
    }

    private function parseBooleanSetting(string $value, bool $defaultValue) : bool {
        if (trim($value) === 'true' || trim($value) === '1') {
            return true;
        }
        if (trim($value) === 'false' || trim($value) === '0') {
            return false;
        }
        return $defaultValue;
    }

}
