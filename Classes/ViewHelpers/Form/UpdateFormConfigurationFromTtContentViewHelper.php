<?php

namespace WapplerSystems\WsT3bootstrap\ViewHelpers\Form;


use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 */
class UpdateFormConfigurationFromTtContentViewHelper extends AbstractViewHelper
{

    public function initializeArguments()
    {
        parent::initializeArguments();

        $this->registerArgument('formConfiguration', 'array', '', true);
        $this->registerArgument('field', 'string', '', true);
        $this->registerArgument('optionName', 'string', '', true);
    }

    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext)
    {

        $formConfiguration = $arguments['formConfiguration'];
        $identifier = $formConfiguration['identifier'];

        $array = explode('-', $identifier);
        $uid = (int)array_pop($array);

        $values = BackendUtility::getRecord('tt_content', $uid, $arguments['field']);
        if (isset($values[$arguments['field']])) {
            $formConfiguration['renderingOptions'][$arguments['optionName']] = $values[$arguments['field']];
        }

        return $formConfiguration;
    }


}
