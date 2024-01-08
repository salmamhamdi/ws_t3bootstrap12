<?php
namespace WapplerSystems\WsT3bootstrap\ViewHelpers\Page;

/*
 * This file is part of the FluidTYPO3/Vhs project under GPLv2 or later.
 *
 * For the full copyright and license information, please read the
 * LICENSE.md file that was distributed with this source code.
 */



class CategoriesViewHelper extends \TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper {


	/**
	 * @var \WapplerSystems\WsT3bootstrap\Domain\Repository\PageRepository
	 * @inject
	 */
	protected $pageRepository;



	/**
	 * @return void
	 */
	public function initializeArguments() {
		$this->registerArgument('pageUid', 'integer', 'If specified, this UID will be used to fetch page data instead of using the current page.', FALSE, 0);
	}

	/**
	 * @return mixed
	 */
	public function render() {
		// Get page via pageUid argument or current id
		$pageUid = intval($this->arguments['pageUid']);
		if (0 === $pageUid) {
			$pageUid = $GLOBALS['TSFE']->id;
		}

		$page = $this->pageRepository->findByUid($pageUid);


		if ($page) {
			return $page->getCategories();
		}


		return array();
	}

}
