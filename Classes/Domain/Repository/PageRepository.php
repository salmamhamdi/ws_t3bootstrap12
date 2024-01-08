<?php
namespace WapplerSystems\WsT3bootstrap\Domain\Repository;


class PageRepository extends \TYPO3\CMS\Extbase\Persistence\Repository {
	

	public function findByCategory(\Shift\Uirtemplate\Domain\Model\Category $category,$limit = 0,$offset = 0) {

		$query = $this->createQuery();
		$query->getQuerySettings()->setRespectStoragePage(FALSE);

		$query->matching(
			$query->contains('categories', $category)
		);

		if ($limit > 0) {
			$query->setLimit($limit);
		}
		$query->setOffset($offset);

		return $query->execute();
	}




}
