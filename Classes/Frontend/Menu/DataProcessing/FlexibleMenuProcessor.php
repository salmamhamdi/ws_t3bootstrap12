<?php
declare(strict_types = 1);

namespace WapplerSystems\WsT3bootstrap\Frontend\Menu\DataProcessing;

use TYPO3\CMS\Frontend\DataProcessing\MenuProcessor;

class FlexibleMenuProcessor extends MenuProcessor {

    /**
     * Prepare Configuration
     */
    public function prepareConfiguration()
    {
        parent::prepareConfiguration();

        $levels = $this->cObj->data['levels'] ?? $this->menuConfig['levels'];
        $this->menuLevels = $levels;

        if ((int)$this->cObj->data['layout'] === 220 ) {
            $this->menuConfig['special'] = 'list';
        }
    }

}