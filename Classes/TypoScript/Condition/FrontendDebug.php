<?php

namespace WapplerSystems\WsT3bootstrap\TypoScript\Condition;

class FrontendDebug extends \TYPO3\CMS\Core\Configuration\TypoScript\ConditionMatching\AbstractCondition
{

    /**
     * Evaluate condition
     *
     * @param array $conditionParameters
     * @return bool
     */
    public function matchCondition(array $conditionParameters) {
        return (boolean)$GLOBALS['TYPO3_CONF_VARS']['FE']['debug'];
    }

}