<?php
namespace WapplerSystems\WsT3bootstrap\ViewHelpers\Link;

/*
 * This file is part of the FluidTYPO3/Vhs project under GPLv2 or later.
 *
 * For the full copyright and license information, please read the
 * LICENSE.md file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * ### TypolinkViewhelper
 *
 * Renders a link with the TypoLink function.
 * Can be used with the LinkWizard
 *
 * For more info on the typolink function, please consult the offical core-documentation:
 * http://docs.typo3.org/typo3cms/TyposcriptIn45MinutesTutorial/TypoScriptFunctions/Typolink/Index.html
 *
 * ### Examples
 *
 *     <!-- tag -->
 *     <v:link.typolink configuration="{typoLinkConfiguration}" />
 *     <v:link.typolink configuration="{object}">My LinkText</v:link.typolink>
 *     <!-- with a {parameter} variable containing the PID -->
 *     <v:link.typolink configuration="{parameter: parameter}" />
 *     <!-- with a {fields.link} variable from the LinkWizard (incl. 'class', 'target' etc.) inside a flux form -->
 *     <v:link.typolink configuration="{parameter: fields.link}" />
 *     <!-- same with a {page} variable from fluidpages -->
 *     <v:link.typolink configuration="{parameter: page.uid}" />
 *     <!-- With extensive configuration -->
 *     <v:link.typolink configuration="{parameter: page.uid, additionalParams: '&print=1', title: 'Follow the link'}">Click Me!</v:link.typolink>
 *
 * @author Sven Wappler
 * @subpackage ViewHelpers\Link
 */
class TypolinkViewHelper extends AbstractViewHelper {

	/**
	 * Initializes the arguments for the ViewHelper
	 */
	public function initializeArguments() {
		$this->registerArgument('configuration', 'array', 'The typoLink configuration', TRUE);
		$this->registerArgument('additionalClass', 'string', 'Additional link class', FALSE);
		$this->registerArgument('lightboxGroup', 'string', 'Additional link class', FALSE);
	}

	/**
	 * @return mixed
	 */
	public function render() {

		$conf = $this->arguments['configuration'];

		if (isset($conf['parameter']) && !empty($this->arguments['additionalClass'])) {
			$parameter = $conf['parameter'];


			$link_paramA = GeneralUtility::unQuoteFilenames($parameter, TRUE);
			if (isset($link_paramA[2])) {
				if (trim($link_paramA[2]) == "-") {
					$link_paramA[2] = $this->arguments['additionalClass'];
				} else {
					$link_paramA[2] .= " ".$this->arguments['additionalClass'];
				}
			} else {
				if (!isset($link_paramA[1])) $link_paramA[1] = "-";
				$link_paramA[2] = $this->arguments['additionalClass'];
			}

			$sParameter = "";
			foreach ($link_paramA as $link_param) {
				$sParameter .= (strpos($link_param," ") !== false) ? '"'.$link_param.'" ' : $link_param." ";
			}
			$conf['parameter'] = $sParameter;
		}
		$conf['ATagParams'] = (!empty($this->arguments['lightboxGroup'])) ? ' data-fancybox-group="'.$this->arguments['lightboxGroup'].'"' : "";

		return $GLOBALS['TSFE']->cObj->typoLink($this->renderChildren(), $conf);
	}

}
