<?php
namespace WapplerSystems\WsT3bootstrap\Domain\Model;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Extbase\Domain\Model\Category;


class Page extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {


	/**
	 * @var int
	 */
	protected $doktype = 0;

	/**
	 * @var string
	 */
	protected $title = "";


	/**
	 * @var string
	 */
	protected $subtitle = "";


	/**
	 * @var string
	 */
	protected $desciption = "";


	/**
	 * @var string
	 */
	protected $abstract = "";


	/**
	 * media
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
	 */
	protected $media = NULL;



	/**
	 *
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\Category>
	 */
	protected $categories = NULL;



	/* ---------------------------- Getter and setter -------------------------------- */

	/**
	 * @return int
	 */
	public function getDoktype()
	{
		return $this->doktype;
	}

	/**
	 * @param int $doktype
	 */
	public function setDoktype($doktype)
	{
		$this->doktype = $doktype;
	}

	/**
	 * @return string
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * @param string $title
	 */
	public function setTitle($title)
	{
		$this->title = $title;
	}

	/**
	 * @return string
	 */
	public function getSubtitle()
	{
		return $this->subtitle;
	}

	/**
	 * @param string $subtitle
	 */
	public function setSubtitle($subtitle)
	{
		$this->subtitle = $subtitle;
	}

	/**
	 * @return string
	 */
	public function getDesciption()
	{
		return $this->desciption;
	}

	/**
	 * @param string $desciption
	 */
	public function setDesciption($desciption)
	{
		$this->desciption = $desciption;
	}

	/**
	 * @return string
	 */
	public function getAbstract()
	{
		return $this->abstract;
	}

	/**
	 * @param string $abstract
	 */
	public function setAbstract($abstract)
	{
		$this->abstract = $abstract;
	}

	/**
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
	 */
	public function getMedia()
	{
		return $this->media;
	}

	/**
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $media
	 */
	public function setMedia($media)
	{
		$this->media = $media;
	}




	/**
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
	 */
	public function getCategories()
	{
		return $this->categories;
	}

	/**
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $categories
	 */
	public function setCategories($categories)
	{
		$this->categories = $categories;
	}


	/**
	 * @param Category $category
	 * @return bool
	 */
	public function hasCategory(Category $category) {
		foreach ($this->categories as $cat) {
			if ($category->getUid() == $cat->getUid()) {
				return true;
			}
		}
		return false;
	}






}
