<?php
namespace WapplerSystems\WsT3bootstrap\Frontend\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Site\Entity\Site;

class SiteBaseRedirectResolver extends \TYPO3\CMS\Frontend\Middleware\SiteBaseRedirectResolver {


    /**
     * Redirect to default language if required
     *
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {

        if (($site = $request->getAttribute('site', null)) instanceof Site &&
            ($configuration = $site->getConfiguration()['routes'] ?? null)
        ) {
            $path = ltrim($request->getUri()->getPath(), '/');
            $routeConfig = $this->getApplicableStaticRoute($configuration, $site, $path);
            if (is_array($routeConfig)) {
                return $handler->handle($request);
            }
        }
        return parent::process($request, $handler);
    }


    /**
     * Find the proper configuration for the static route in the static route configuration. Mainly:
     * - needs to have a valid "route" property
     * - needs to have a "type"
     *
     * @param array $staticRouteConfiguration the "routes" part of the site configuration
     * @param Site $site the current site where the configuration is based on
     * @param string $uriPath the path of the current request - used to match the "route" value of a single static route
     * @return array|null the configuration for the static route that matches, or null if no route is given
     */
    protected function getApplicableStaticRoute(array $staticRouteConfiguration, Site $site, string $uriPath): ?array
    {
        $routeNames = array_map(static function (?string $route) use ($site) {
            if ($route === null || $route === '') {
                return null;
            }
            return ltrim(trim($site->getBase()->getPath(), '/') . '/' . ltrim($route, '/'), '/');
        }, array_column($staticRouteConfiguration, 'route'));
        // Remove empty routes which would throw an error (could happen within creating a false route in the GUI)
        $routeNames = array_filter($routeNames);

        if (in_array($uriPath, $routeNames, true)) {
            $key = array_search($uriPath, $routeNames, true);
            // Only allow routes with a type "given"
            if (isset($staticRouteConfiguration[$key]['type'])) {
                return $staticRouteConfiguration[$key];
            }
        }
        return null;
    }

}
