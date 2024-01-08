<?php
namespace WapplerSystems\WsT3bootstrap\ViewHelpers;




class LanguageUidViewHelper extends \TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper
{


    public function render() {

        return $GLOBALS['TSFE']->sys_language_uid;
    }


}
