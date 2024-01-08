<?php

namespace WapplerSystems\WsT3bootstrap\ViewHelpers;


use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Service\ImageService;
use TYPO3\CMS\Fluid\View\TemplateView;

/**
 *
 */
class PictureViewHelper extends \TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper
{


    /**
     * @var FileInterface
     */
    protected $image;

    /**
     * @var string
     */
    protected $classNames;

    /**
     * @var string
     */
    protected $additionalAttributes;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $alt;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $loading;

    /**
     * @var bool
     */
    protected $useBootstrapCropVariants;

    /**
     * @var mixed
     */
    protected $cropVariants;

    /**
     * @var string
     */
    protected $imgTag;

    /**
     * @var array
     */
    protected $settings = [];


    protected $containerless = false;


    protected $escapeOutput = false;


    public function initializeArguments()
    {
        $this->registerArgument('additionalAttributes', 'mixed', 'array of additional attributes', false);
        $this->registerArgument('id', 'string', 'The element id', false);
        $this->registerArgument('class', 'string', 'Additional classes', false);
        $this->registerArgument('src', 'string', '', false);
        $this->registerArgument('useCropVariant', 'boolean', '(deprecated)', false);
        $this->registerArgument('cropVariants', 'mixed', '', false, ['xs' => 'default', 'sm' => 'default', 'md' => 'default', 'lg' => 'default', 'xl' => 'default', 'xxl' => 'default', 'full' => 'default']);
        $this->registerArgument('treatIdAsReference', 'boolean', '', false, false);
        $this->registerArgument('image', 'mixed', '', false);
        $this->registerArgument('alt', 'string', '', false);
        $this->registerArgument('title', 'string', '', false);
        $this->registerArgument('useBootstrapCropVariants', 'boolean', 'Use Bootstrap compatible CropVariants', false, false);
        $this->registerArgument('containerless', 'bool', 'Render image without container', false, false);
        $this->registerArgument('loading', 'string', 'Sets the loading attribute', false, 'auto');

    }

    public function initialize()
    {
        $this->image = $this->arguments['image'];
        $this->id = $this->arguments['id'] ?? bin2hex(random_bytes(5));

        if ($this->image === null) {

            $src = $this->arguments['src'];
            $treatIdAsReference = $this->arguments['treatIdAsReference'];

            $imageService = self::getImageService();

            $this->image = $imageService->getImage($src, null, $treatIdAsReference);

        }

        if (method_exists($this->image, 'getOriginalResource') && $this->image->getOriginalResource() instanceof \TYPO3\CMS\Core\Resource\FileReference) {
            $this->image = $this->image->getOriginalResource();
        }

        $this->classNames = $this->arguments['class'];
        $this->title = $this->arguments['title'];
        $this->alt = $this->arguments['alt'];
        $this->loading = ($this->arguments['loading'] !== 'auto') ? $this->arguments['loading'] : ($GLOBALS['TSFE']->register['imageLoadingBehaviour'] ?? 'auto');
        $this->useBootstrapCropVariants = $this->arguments['useBootstrapCropVariants'];
        $this->cropVariants = $this->arguments['cropVariants'];
        $this->containerless = $this->arguments['containerless'] ?? false;


        $all = GeneralUtility::makeInstance(ConfigurationManager::class)->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT
        );
        $segments = explode('.', 'plugin.tx_wst3bootstrap.settings');
        $value = $all;
        foreach ($segments as $path) {
            $value = (true === isset($value[$path . '.']) ? $value[$path . '.'] : $value[$path]);
        }
        if (true === \is_array($value)) {
            $value = GeneralUtility::removeDotsFromTS($value);
        }
        $this->settings = $value;

        $this->imgTag = $this->settings['imgTag'] ?? 'picture';

        if ($this->image->getMimeType() === 'image/svg+xml') {
            $this->imgTag = 'img';
        }
        if ($this->isAnimatedGif($this->image)) {
            $this->imgTag = 'scaled';
        }

        if (($this->arguments['src'] === null && $this->arguments['image'] === null) || ($this->arguments['src'] !== null && $this->arguments['image'] !== null)) {
            throw new \UnexpectedValueException('You must either specify a string src or a File object.', 1382284105);
        }

        parent::initialize();

    }


    /**
     */
    public function render()
    {

        $view = new TemplateView();
        $view->setTemplatePathAndFilename('EXT:ws_t3bootstrap/Resources/Private/Templates/Picture.html');

        $breakpoints = $this->settings['breakpoints'];
        if ($this->containerless) {
            $breakpoints = $this->settings['containerlessBreakpoints'];
            $this->classNames .= ' '.$this->settings['fullwidthClass'];
        }

        if ($this->useBootstrapCropVariants) {
            $this->cropVariants = [];
            foreach ($breakpoints as $breakpoint => $breakpointData) {
                $this->cropVariants[$breakpoint] = $breakpointData['cropVariant'] ?? 'default';
            }
        }

        $view->assignMultiple([
            'settings' => $this->settings,
            'containerWidths' => $this->settings['containerWidths'],
            'breakpoints' => $breakpoints,
            'image' => $this->image,
            'class' => $this->classNames,
            'id' => $this->id,
            'loading' => $this->loading,
            'title' => $this->title,
            'alt' => $this->alt,
            'useBootstrapCropVariants' => $this->useBootstrapCropVariants,
            'imgTag' => $this->imgTag,
            'cropVariants' => $this->cropVariants,
            'additionalAttributes' => $this->additionalAttributes,
        ]);

        return $view->render();
    }


    /**
     * Return an instance of ImageService using object manager
     *
     * @return ImageService
     */
    protected static function getImageService(): ImageService
    {
        return GeneralUtility::makeInstance(ImageService::class);
    }

    private function isAnimatedGif(FileInterface $image)
    {

        if ($image->getMimeType() !== 'image/gif') {
            return false;
        }

        $content = $image->getContents();

        $str_loc = 0;
        $count = 0;
        while ($count < 2) # There is no point in continuing after we find a 2nd frame
        {

            $where1 = strpos($content, "\x00\x21\xF9\x04", $str_loc);
            if ($where1 === FALSE) {
                break;
            }

            $str_loc = $where1 + 1;
            $where2 = strpos($content, "\x00\x2C", $str_loc);
            if ($where2 === FALSE) {
                break;
            }

            if ($where1 + 8 === $where2) {
                $count++;
            }
            $str_loc = $where2 + 1;
        }

        return $count > 1;
    }

}
