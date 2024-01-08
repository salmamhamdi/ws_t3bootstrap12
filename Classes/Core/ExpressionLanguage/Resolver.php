<?php

declare(strict_types=1);

namespace WapplerSystems\WsT3bootstrap\Core\ExpressionLanguage;


use TYPO3\CMS\Form\Domain\Model\Renderable\VariableRenderableInterface;

/**
 * Class Resolver
 */
class Resolver extends \TYPO3\CMS\Core\ExpressionLanguage\Resolver
{

    /**
     * @var VariableRenderableInterface
     */
    protected $renderable;

    /**
     * @param VariableRenderableInterface $renderable
     */
    public function setRenderable(VariableRenderableInterface $renderable): void
    {
        $this->renderable = $renderable;
    }

    /**
     * Evaluate an expression.
     */
    public function evaluate(string $expression, array $contextVariables = []): mixed
    {
        return parent::evaluate($expression,array_merge($contextVariables,['renderable' => $this->renderable]));
    }

}
