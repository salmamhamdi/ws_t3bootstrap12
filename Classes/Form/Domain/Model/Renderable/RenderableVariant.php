<?php
namespace WapplerSystems\WsT3bootstrap\Form\Domain\Model\Renderable;


use TYPO3\CMS\Core\ExpressionLanguage\Resolver;

class RenderableVariant extends \TYPO3\CMS\Form\Domain\Model\Renderable\RenderableVariant {


    /**
     * @param Resolver $conditionResolver
     * @return bool
     */
    public function conditionMatches(Resolver $conditionResolver): bool
    {
        if (empty($this->condition)) {
            return false;
        }

        if ($conditionResolver instanceof \WapplerSystems\WsT3bootstrap\Core\ExpressionLanguage\Resolver) {
            $conditionResolver->setRenderable($this->renderable);
        }

        return $conditionResolver->evaluate($this->condition);
    }

}
