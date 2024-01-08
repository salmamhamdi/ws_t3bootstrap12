<?php

use FluidTYPO3\Flux\Core;
use FluidTYPO3\Flux\Integration\ContentTypeBuilder;
use FluidTYPO3\Flux\Utility\CompatibilityRegistry;
use TYPO3\CMS\Backend\Form\FormDataProvider\InlineOverrideChildTca;
use TYPO3\CMS\Backend\Form\FormDataProvider\PageTsConfig;
use TYPO3\CMS\Core\Cache\Frontend\VariableFrontend;
use TYPO3\CMS\Core\Resource\Rendering\RendererRegistry;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use WapplerSystems\WsT3bootstrap\Backend\Form\Element\SelectIconElement;
use WapplerSystems\WsT3bootstrap\Backend\Form\Element\SelectSingleWithTypoScriptPlaceholderElement;
use WapplerSystems\WsT3bootstrap\Backend\Form\FieldWizard\SelectBigIcons;
use WapplerSystems\WsT3bootstrap\Controller\PageController;
use WapplerSystems\WsT3bootstrap\Hooks\MenuCacheUpdateHooks;
use WapplerSystems\WsT3bootstrap\Hooks\PageRendererHooks;
use WapplerSystems\WsT3bootstrap\Resource\Rendering\VimeoRenderer;
use WapplerSystems\WsT3bootstrap\Resource\Rendering\YouTubeRenderer;
use WapplerSystems\WsT3bootstrap\Updates\AddBackendLayoutNextLevelToRootPages;
use WapplerSystems\WsT3bootstrap\Updates\CleanFedPageControllerColumns;
use WapplerSystems\WsT3bootstrap\Updates\ConvertBootstrap3Columns;
use WapplerSystems\WsT3bootstrap\Updates\ConvertExtendedDesigns;
use WapplerSystems\WsT3bootstrap\Updates\ConvertFluidcontent;
use WapplerSystems\WsT3bootstrap\Updates\ConvertFluidcontentContent;
use WapplerSystems\WsT3bootstrap\Updates\ConvertHeaderSize;
use WapplerSystems\WsT3bootstrap\Updates\ConvertOldFluxCType;
use WapplerSystems\WsT3bootstrap\Updates\CopyImageColsXL2XXL;
use WapplerSystems\WsT3bootstrap\Updates\FluidpagesToBackendLayouts;
use WapplerSystems\WsT3bootstrap\Updates\RemoveFluxColumnOffset;
use WapplerSystems\WsT3bootstrap\Updates\RewriteIconPaths;


$rendererRegistry = GeneralUtility::makeInstance(RendererRegistry::class);
$rendererRegistry->registerRendererClass(YouTubeRenderer::class);
$rendererRegistry->registerRendererClass(VimeoRenderer::class);


$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][TYPO3\CMS\Core\ExpressionLanguage\Resolver::class] = [
    'className' => WapplerSystems\WsT3bootstrap\Core\ExpressionLanguage\Resolver::class
];
$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][TYPO3\CMS\Form\Domain\Model\Renderable\RenderableVariant::class] = [
    'className' => WapplerSystems\WsT3bootstrap\Form\Domain\Model\Renderable\RenderableVariant::class
];
$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][TYPO3\CMS\Frontend\Middleware\SiteBaseRedirectResolver::class] = [
    'className' => WapplerSystems\WsT3bootstrap\Frontend\Middleware\SiteBaseRedirectResolver::class
];
$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][TYPO3\CMS\Core\Page\PageRenderer::class] = [
    'className' => WapplerSystems\WsT3bootstrap\Page\PageRenderer::class
];


$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][1508619828] = [
    'nodeName' => 'selectBigIcons',
    'priority' => '70',
    'class' => SelectBigIcons::class,
];

$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][1583516651] = [
    'nodeName' => 'selectIcon',
    'priority' => '70',
    'class' => SelectIconElement::class,
];
$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][1587333153] = [
    'nodeName' => 'selectSingleWithTypoScriptPlaceholderT3bootstrap',
    'priority' => '70',
    'class' => SelectSingleWithTypoScriptPlaceholderElement::class,
];


if (ExtensionManagementUtility::isLoaded('flux')) {
    try {
        Core::registerProviderExtensionKey('ws_t3bootstrap', 'Content');
    } catch (\BadFunctionCallException $ex) {

    }
}


ExtensionUtility::configurePlugin(
    'WsT3bootstrap',
    'Page',
    [
        PageController::class => 'render,error',
    ],
    [],
    ExtensionUtility::PLUGIN_TYPE_PLUGIN
);


/* Menu Cache */
if (!is_array($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['wst3bootstrap_menu'] ?? null)) {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['wst3bootstrap_menu'] = [
        'frontend' => VariableFrontend::class,
        'options' => [
            'defaultLifetime' => 0
        ],
        'groups' => ['pages', 'all']
    ];
}

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = MenuCacheUpdateHooks::class;
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processCmdmapClass'][] = MenuCacheUpdateHooks::class;
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['moveRecordClass'][] = MenuCacheUpdateHooks::class;

/* disable deprecation log */
unset($GLOBALS['TYPO3_CONF_VARS']['LOG']['TYPO3']['CMS']['deprecations']);


$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['formDataGroup']['tcaDatabaseRecord'][WapplerSystems\WsT3bootstrap\Backend\Form\FormDataProvider\PageTsConfigOverrideChildTca::class] = [
    'depends' => [
        PageTsConfig::class,
    ],
    'before' => [
        InlineOverrideChildTca::class
    ]
];


$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update']['fluidpagesToBackendLayouts']
    = FluidpagesToBackendLayouts::class;

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update']['addBackendLayoutNextLevelToRootPages']
    = AddBackendLayoutNextLevelToRootPages::class;

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update']['convertOldFluxCType']
    = ConvertOldFluxCType::class;

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update']['convertFluidcontent']
    = ConvertFluidcontent::class;

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update']['convertFluidcontentContent']
    = ConvertFluidcontentContent::class;

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update']['cleanFedPageControllerColumns']
    = CleanFedPageControllerColumns::class;

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update']['convertExtendedDesigns']
    = ConvertExtendedDesigns::class;

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update']['convertBootstrap3Columns']
    = ConvertBootstrap3Columns::class;

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update']['convertHeadersize']
    = ConvertHeaderSize::class;

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update']['removeFluxColumnOffset']
    = RemoveFluxColumnOffset::class;

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update']['copyImageColsXL2XXL']
    = CopyImageColsXL2XXL::class;

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update']['rewriteIconPaths']
    = RewriteIconPaths::class;


$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_pagerenderer.php']['render-postProcess'][] = PageRendererHooks::class . '->postRenderHook';
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_pagerenderer.php']['render-postProcess'][] = PageRendererHooks::class . '->changeTemplateFile';


CompatibilityRegistry::register(
    ContentTypeBuilder::DEFAULT_SHOWITEM,
    [
        '10.4.0' => '
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.general;general,
                --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.headers;headers,
                --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:icon;icon,
            --div--;LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:palette.content,
                pi_flexform,
            --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,
                --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.frames;frames,
                --palette--;LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:palette.responsive;responsive,
                --palette--;LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:palette.background;background,
                --palette--;LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:palette.aos;aos,
                --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.appearanceLinks;appearanceLinks,
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
                --palette--;;language,
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                --palette--;;hidden,
                --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.access;access,
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
                categories,
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
                rowDescription'
    ]
);

ExtensionManagementUtility::addTypoScriptSetup(
    'module.tx_form {
    settings {
        yamlConfigurations {
            71 = EXT:ws_t3bootstrap/Configuration/Yaml/FormSetup.yaml
        }
    }
}'
);
