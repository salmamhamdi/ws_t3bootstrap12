<?php
namespace WapplerSystems\WsT3bootstrap\ViewHelpers;


use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;

/**
 *
 */
class ImageScaleViewHelper extends \TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper
{

    /**
     * @var ConfigurationManagerInterface
     */
    protected $configurationManager;


    /**
     * @var bool
     */
    protected $escapeOutput = false;

    protected $xs = 12;
    protected $sm = 12;
    protected $md = 12;
    protected $lg = 12;
    protected $xl = 12;
    protected $xxl = 12;


    /**
     * @param ConfigurationManagerInterface $configurationManager
     * @return void
     */
    public function injectConfigurationManager(ConfigurationManagerInterface $configurationManager)
    {
        $this->configurationManager = $configurationManager;
    }


    public function initializeArguments()
    {
        parent::initializeArguments();

        $this->registerArgument('xs', 'int', '', false, 12);
        $this->registerArgument('sm', 'int', '', false);
        $this->registerArgument('md', 'int', '', false);
        $this->registerArgument('lg', 'int', '', false);
        $this->registerArgument('xl', 'int', '', false);
        $this->registerArgument('xxl', 'int', '', false);
    }


    public function setArguments(array $arguments)
    {
        $this->arguments = $arguments;

        /* zur Sicherheit, falls fluid mit Strings reinkommt */

        $xs = ($this->arguments['xs'] === '') ? null : $this->arguments['xs'];
        $sm = ($this->arguments['sm'] === '') ? null : $this->arguments['sm'];
        $md = ($this->arguments['md'] === '') ? null : $this->arguments['md'];
        $lg = ($this->arguments['lg'] === '') ? null : $this->arguments['lg'];
        $xl = ($this->arguments['xl'] === '') ? null : $this->arguments['xl'];
        $xxl = ($this->arguments['xxl'] === '') ? null : $this->arguments['xxl'];

        $this->xs = ($xs !== null) ? (int)$xs : $xs;
        $this->sm = ($sm !== null) ? (int)$sm : $sm;
        $this->md = ($md !== null) ? (int)$md : $md;
        $this->lg = ($lg !== null) ? (int)$lg : $lg;
        $this->xl = ($xl !== null) ? (int)$xl : $xl;
        $this->xxl = ($xxl !== null) ? (int)$xxl : $xxl;

        $columns = $this->getColumns();

        if ($this->xs === null) {
            $this->xs = $columns;
        }
        if ($this->sm === null) {
            $this->sm = $this->xs;
        }
        if ($this->md === null) {
            $this->md = $this->sm;
        }
        if ($this->lg === null) {
            $this->lg = $this->md;
        }
        if ($this->xl === null) {
            $this->xl = $this->lg;
        }
        if ($this->xxl === null) {
            $this->xxl = $this->xl;
        }

    }


    /**
     *
     * @return string Rendered string
     * @api
     */
    public function render()
    {

        $columns = $this->getColumns();

        $xsScale = isset($GLOBALS['TSFE']->register['xsScale']) ? $GLOBALS['TSFE']->register['xsScale'] : 1;
        $smScale = isset($GLOBALS['TSFE']->register['smScale']) ? $GLOBALS['TSFE']->register['smScale'] : 1;
        $mdScale = isset($GLOBALS['TSFE']->register['mdScale']) ? $GLOBALS['TSFE']->register['mdScale'] : 1;
        $lgScale = isset($GLOBALS['TSFE']->register['lgScale']) ? $GLOBALS['TSFE']->register['lgScale'] : 1;
        $xlScale = isset($GLOBALS['TSFE']->register['xlScale']) ? $GLOBALS['TSFE']->register['xlScale'] : 1;
        $xxlScale = isset($GLOBALS['TSFE']->register['xxlScale']) ? $GLOBALS['TSFE']->register['xxlScale'] : 1;

        $xsScaleNew = $xsScale * (int)$this->xs / $columns;
        $smScaleNew = $smScale * (int)$this->sm / $columns;
        $mdScaleNew = $mdScale * (int)$this->md / $columns;
        $lgScaleNew = $lgScale * (int)$this->lg / $columns;
        $xlScaleNew = $xlScale * (int)$this->xl / $columns;
        $xxlScaleNew = $xxlScale * (int)$this->xxl / $columns;

        $GLOBALS['TSFE']->register['xsScale'] = $xsScaleNew;
        $GLOBALS['TSFE']->register['smScale'] = $smScaleNew;
        $GLOBALS['TSFE']->register['mdScale'] = $mdScaleNew;
        $GLOBALS['TSFE']->register['lgScale'] = $lgScaleNew;
        $GLOBALS['TSFE']->register['xlScale'] = $xlScaleNew;
        $GLOBALS['TSFE']->register['xxlScale'] = $xxlScaleNew;


        if ($this->templateVariableContainer->exists('xsScale')) {
            $this->templateVariableContainer->remove('xsScale');
        }
        $this->templateVariableContainer->add('xsScale', $xsScaleNew);

        if ($this->templateVariableContainer->exists('smScale')) {
            $this->templateVariableContainer->remove('smScale');
        }
        $this->templateVariableContainer->add('smScale', $smScaleNew);

        if ($this->templateVariableContainer->exists('mdScale')) {
            $this->templateVariableContainer->remove('mdScale');
        }
        $this->templateVariableContainer->add('mdScale', $mdScaleNew);

        if ($this->templateVariableContainer->exists('lgScale')) {
            $this->templateVariableContainer->remove('lgScale');
        }
        $this->templateVariableContainer->add('lgScale', $lgScaleNew);

        if ($this->templateVariableContainer->exists('xlScale')) {
            $this->templateVariableContainer->remove('xlScale');
        }
        $this->templateVariableContainer->add('xlScale', $xlScaleNew);

        if ($this->templateVariableContainer->exists('xxlScale')) {
            $this->templateVariableContainer->remove('xxlScale');
        }
        $this->templateVariableContainer->add('xxlScale', $xxlScaleNew);

        $output = $this->renderChildren();

        $GLOBALS['TSFE']->register['xsScale'] = $xsScale;
        $GLOBALS['TSFE']->register['smScale'] = $smScale;
        $GLOBALS['TSFE']->register['mdScale'] = $mdScale;
        $GLOBALS['TSFE']->register['lgScale'] = $lgScale;
        $GLOBALS['TSFE']->register['xlScale'] = $xlScale;
        $GLOBALS['TSFE']->register['xxlScale'] = $xxlScale;

        return $output;
    }


    protected function getTyposcript($path = null)
    {
        if (true === empty($path)) {
            return null;
        }
        $all = $this->configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
        $segments = explode('.', $path);
        $value = $all;
        foreach ($segments as $segment) {
            $value = (true === isset($value[$segment . '.']) ? $value[$segment . '.'] : $value[$segment]);
        }
        if (true === is_array($value)) {
            $value = GeneralUtility::removeDotsFromTS($value);
        }
        return $value;
    }

    /**
     * @return int
     */
    protected function getColumns()
    {
        $columns = (int)$this->getTyposcript('plugin.tx_wst3bootstrap.settings.gridColumns');
        if (!$columns) {
            $columns = 12;
        }
        return $columns;
    }
}
