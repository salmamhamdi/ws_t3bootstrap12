<?php
namespace WapplerSystems\WsT3bootstrap\ViewHelpers\Variable\Register;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * ### Variable\Register: Set
 *
 *
 */
class SetViewHelper extends AbstractViewHelper
{

    /**
     * @var boolean
     */
    protected $escapeOutput = false;

    /**
     * @return void
     */
    public function initializeArguments()
    {
        $this->registerArgument('value', 'mixed', 'Value to set', true);
        $this->registerArgument('name', 'string', 'Name of register', true);
    }

    /**
     *
     * @return string Rendered string
     * @api
     */
    public function render()
    {
        $oldValue = $GLOBALS['TSFE']->register[$this->arguments['name']] ?? null;
        if ($this->arguments['value'] === 'true') $this->arguments['value'] = true;
        if ($this->arguments['value'] === 'false') $this->arguments['value'] = false;
        $GLOBALS['TSFE']->register[$this->arguments['name']] = $this->arguments['value'];

        $output = $this->renderChildren();

        if ($oldValue === null) {
            unset($GLOBALS['TSFE']->register[$this->arguments['name']]);
        } else {
            $GLOBALS['TSFE']->register[$this->arguments['name']] = $oldValue;
        }

        return $output;
    }

}
