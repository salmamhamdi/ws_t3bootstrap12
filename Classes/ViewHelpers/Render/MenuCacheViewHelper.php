<?php
namespace WapplerSystems\WsT3bootstrap\ViewHelpers\Render;

/*
 * This file is part of the FluidTYPO3/Vhs project under GPLv2 or later.
 *
 * For the full copyright and license information, please read the
 * LICENSE.md file that was distributed with this source code.
 */

use FluidTYPO3\Vhs\ViewHelpers\Render\AbstractRenderViewHelper;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface;

/**
 *
 */
class MenuCacheViewHelper extends AbstractRenderViewHelper
{

    const ID_PREFIX = 'wst3bootstrap-render-menu-cache-viewhelper';

    const ID_SEPARATOR = '-';

    /**
     * @var boolean
     */
    protected $escapeChildren = false;

    /**
     * @return void
     */
    public function initializeArguments()
    {
        $this->registerArgument('content', 'string', 'Content to be cached');
        $this->registerArgument('identity', 'string', 'Identity for cached entry', true);
        $this->registerArgument('enabled', 'boolean', 'Enable caching', false, true);
        parent::initializeArguments();
    }


    protected function render() {
        if (!$this->arguments['enabled']) {
            return $this->renderChildren();
        }
        $identity = $this->arguments['identity'];
        if (false === ctype_alnum(preg_replace('/[\-_]/i', '', $identity))) {
            if (true === $identity instanceof DomainObjectInterface) {
                $identity = get_class($identity) . self::ID_SEPARATOR . $identity->getUid();
            } elseif (true === method_exists($identity, '__toString')) {
                $identity = (string) $identity;
            } else {
                throw new \RuntimeException(
                    'Parameter $identity for Render/CacheViewHelper was not a string or a string-convertible object',
                    1352581782
                );
            }
        }

        // Usergroups
        if (false === empty($GLOBALS['TSFE']->fe_user)) {
            foreach ($GLOBALS['TSFE']->fe_user->groupData['uid'] as $groupUid) {
                $identity .= '-g'.$groupUid;
            }
        }

        // Hash the cache-key to circumvent disallowed chars
        $identity = sha1($identity);
        if (true === $this->has($identity)) {
            return $this->retrieve($identity);
        }
        $content = $this->renderChildren();
        $this->store($content, $identity);
        return $content;
    }

    /**
     * @param string $id
     * @return boolean
     */
    protected function has($id)
    {
        //if (isset($GLOBALS['BE_USER']->user)) return false;
        return (boolean) $this->getCache()->has(static::ID_PREFIX . static::ID_SEPARATOR . $id);
    }

    /**
     * @param mixed $value
     * @param string $id
     * @return void
     */
    protected function store($value, $id)
    {
        $this->getCache()->set(static::ID_PREFIX . static::ID_SEPARATOR . $id, $value);
    }

    /**
     * @param string $id
     * @return mixed
     */
    protected function retrieve($id)
    {
        $cache = $this->getCache();
        if ($cache->has(static::ID_PREFIX . static::ID_SEPARATOR . $id)) {
            return $cache->get(static::ID_PREFIX . static::ID_SEPARATOR . $id);
        }
        return null;
    }

    /**
     * @return mixed
     */
    protected function getCache()
    {
        return GeneralUtility::makeInstance(CacheManager::class)->getCache('wst3bootstrap_menu');
    }


}
