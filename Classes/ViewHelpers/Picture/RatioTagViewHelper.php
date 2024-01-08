<?php

namespace WapplerSystems\WsT3bootstrap\ViewHelpers\Picture;


use TYPO3\CMS\Core\Imaging\ImageManipulation\CropVariantCollection;
use TYPO3\CMS\Core\Resource\Exception\ResourceDoesNotExistException;
use TYPO3\CMS\Core\Utility\DebugUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Exception;

/**
 *
 */
class RatioTagViewHelper extends AbstractTagBasedViewHelper
{

    /**
     * @var string
     */
    protected $tagName = 'span';



    /**
     * Initialize
     * @return void
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('variableName', 'string', '', false, '');
        $this->registerArgument('value', 'double', '', false, 0.0);
        $this->registerArgument('class', 'string', '', false, '');
    }



    /**
     *
     * @return string Rendered string
     * @api
     */
    public function render()
    {
        $output = '';
        $this->tag->forceClosingTag(true);
        $this->tag->addAttribute('class',$this->arguments['class']);

        if ($this->arguments['variableName'] !== '') {
            $this->tag->addAttribute('style','aspect-ratio:'.$GLOBALS['TSFE']->register[$this->arguments['variableName']]);
        }
        if ($this->arguments['value'] !== 0.0) {
            $this->tag->addAttribute('style','aspect-ratio:'.$this->arguments['value']);
        }
        $output .= $this->tag->render();
        return $output;
    }


}
