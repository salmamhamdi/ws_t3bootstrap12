<?php

namespace WapplerSystems\WsT3bootstrap\Utility;


use TYPO3\CMS\Core\TypoScript\AST\Node\RootNode;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ContentElementSettings
{

    /**
     * @return mixed
     */
    public static function getSettingByTypeAndPath($type, $path, RootNode $typoScript)
    {
        $segments = explode('.', $path);
        $typePath = $segments[0] . '.types.' . $type . '.' . implode('.', array_slice($segments, 1));
        $typeValue = self::getTypoScriptValue('plugin.tx_wst3bootstrap.settings.' . $typePath, $typoScript);
        if ($typeValue !== null) {
            return $typeValue;
        }
        return self::getTypoScriptValue('plugin.tx_wst3bootstrap.settings.' . $path, $typoScript);
    }


    private static function getTypoScriptValue($path, RootNode $typoScript)
    {

        $currentNode = $typoScript;

        $querySegments = GeneralUtility::trimExplode('.', $path);
        $maxSegments = count($querySegments);

        foreach ($querySegments as $key => $segment) {

            $child = $currentNode->getChildByName($segment);
            if ($child !== null) {
                $currentNode = $child;
                if ($key === $maxSegments-1) {
                    return $child->getValue();
                }
            } else {
                return null;
            }
        }
        return null;
    }

}
