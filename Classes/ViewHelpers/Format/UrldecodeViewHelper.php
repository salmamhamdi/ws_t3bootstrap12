<?php

declare(strict_types=1);

namespace WapplerSystems\WsT3bootstrap\ViewHelpers\Format;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithContentArgumentAndRenderStatic;

/**
 * Decodes the given string according to http://www.faqs.org/rfcs/rfc3986.html
 * Applying PHPs :php:`rawurldecode()` function.
 * See https://www.php.net/manual/function.rawurldecode.php.
 *
 * .. note::
 *    The output is not escaped. You may have to ensure proper escaping on your own.
 *
 * Examples
 * ========
 *
 * Default notation
 * ----------------
 *
 * ::
 *
 *    <f:format.rawurldecode>foo @+%/</f:format.rawurldecode>
 *
 * ``foo%20%40%2B%25%2F`` :php:`rawurldecode()` applied.
 *
 * Inline notation
 * ---------------
 *
 * ::
 *
 *    {text -> f:format.urldecode()}
 *
 * Url decoded text :php:`rawurldecode()` applied.
 */
final class UrldecodeViewHelper extends AbstractViewHelper
{
    use CompileWithContentArgumentAndRenderStatic;

    /**
     * Output is escaped already. We must not escape children, to avoid double encoding.
     *
     * @var bool
     */
    protected $escapeChildren = false;

    public function initializeArguments(): void
    {
        $this->registerArgument('value', 'string', 'string to format');
    }

    /**
     * Escapes special characters with their escaped counterparts as needed using PHPs rawurldecode() function.
     *
     * @see https://www.php.net/manual/function.rawurldecode.php
     * @return mixed
     */
    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext)
    {
        $value = $renderChildrenClosure();
        if (!is_string($value) && !(is_object($value) && method_exists($value, '__toString'))) {
            return $value;
        }
        return rawurldecode((string)$value);
    }

    /**
     * Explicitly set argument name to be used as content.
     */
    public function resolveContentArgumentName(): string
    {
        return 'value';
    }
}
