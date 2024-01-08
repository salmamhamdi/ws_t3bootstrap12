<?php
declare(strict_types = 1);

namespace WapplerSystems\WsT3bootstrap\Controller;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Backend\Controller\AbstractFormEngineAjaxController;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Http\JsonResponse;
use TYPO3\CMS\Core\Utility\DebugUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use WapplerSystems\WsT3bootstrap\Utility\ArrayUtility;

/**
 *
 */
class IconSuggestAjaxController extends AbstractFormEngineAjaxController
{

    /**
     *
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws \RuntimeException
     */
    public function suggestAction(ServerRequestInterface $request): ResponseInterface
    {
        $this->checkRequest($request);

        $results = [];

        $queryParameters = $request->getParsedBody() ?? [];
        $value = $queryParameters['value'];
        $maxItems = $queryParameters['maxItems'] ?? 40;
        $collections = json_decode($queryParameters['collections'],true);

        foreach ($collections as $key => $collection) {
            $files = [];
            $files = GeneralUtility::getAllFilesAndFoldersInPath($files, GeneralUtility::getFileAbsFileName($collection['dir']),'svg' , false, 0 );

            foreach ($files as $file) {

                $basename = basename($file, '.svg');
                if (str_contains($basename, $value)) {
                    $class = $collection['prefix'].$basename;
                    $results[] = ['value' => $collection['dir'].basename($file).';'.$class.';'.$basename, 'svg' => file_get_contents($file), 'label' => $basename, 'class' => $class];
                    if (count($results) > $maxItems) {
                        return new JsonResponse($results);
                    }
                }
            }
        }

        return new JsonResponse($results);
    }

    /**
     * @param ServerRequestInterface $request
     * @return bool
     * @throws \InvalidArgumentException
     */
    protected function checkRequest(ServerRequestInterface $request): bool
    {
        $queryParameters = $request->getParsedBody() ?? [];
        $expectedHash = GeneralUtility::hmac(
            $queryParameters['collections'] ?? '',
            __CLASS__
        );
        if (!hash_equals($expectedHash, $queryParameters['signature'] ?? '')) {
            throw new \InvalidArgumentException(
                'HMAC could not be verified',
                1535137045
            );
        }
        return true;
    }
}
