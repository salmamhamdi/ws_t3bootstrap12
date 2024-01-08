<?php
return [
    'frontend' => [
        'wapplersystems/wst3bootstrap/html-minifier' => [
            'target' => \WapplerSystems\WsT3bootstrap\Frontend\Middleware\HtmlMinifier::class,
            'after' => [
                'typo3/cms-frontend/prepare-tsfe-rendering',
            ],
            'before' => [
                'typo3/cms-frontend/content-length-headers',
            ],
        ],
        'wapplersystems/wst3bootstrap/menu' => [
            'target' => \WapplerSystems\WsT3bootstrap\Frontend\Middleware\AjaxMenu::class,
            'after' => [
                'typo3/cms-frontend/authentication',
            ],
            'before' => [
                'typo3/cms-frontend/page-resolver',
            ],
        ],
    ],
];
