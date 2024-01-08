<?php
namespace WapplerSystems\WsT3bootstrap\Frontend\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Http\Response;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class HtmlMinifier implements MiddlewareInterface
{


    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {

        $response = $handler->handle($request);

        if ((int)$GLOBALS['TYPO3_CONF_VARS']['FE']['debug'] === 1) {
            return $response;
        }
        if (!extension_loaded('tidy')) {
            return $response;
        }
        $useTidy = GeneralUtility::makeInstance(ExtensionConfiguration::class)
            ->get('ws_t3bootstrap', 'tidy');
        if ((int)$useTidy !== 1) return $response;
        if ($response->getStatusCode() !== 200 || strpos($response->getHeaderLine('content-type'),'text/html') === false) {
            return $response;
        }

        $config = [
            'preserve-entities' => true,
            'drop-empty-paras' => true,
            'drop-empty-elements' => false,
            'indent' => false,
            'wrap' => 0];

        $tidy = new \tidy();
        $tidy->parseString($response->getBody()->__toString(), $config, 'utf8');
        $tidy->cleanRepair();

        $newResponse = new Response('php://temp',$response->getStatusCode(),$response->getHeaders(),$response->getReasonPhrase());
        $newResponse->getBody()->write(tidy_get_output($tidy));

        return $newResponse;
    }
}

