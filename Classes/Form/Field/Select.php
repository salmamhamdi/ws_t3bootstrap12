<?php
namespace WapplerSystems\WsT3bootstrap\Form\Field;


/*
 * This file is part of the FluidTYPO3/Flux project under GPLv2 or later.
 *
 * For the full copyright and license information, please read the
 * LICENSE.md file that was distributed with this source code.
 */

use FluidTYPO3\Flux\Form\AbstractMultiValueFormField;

/**
 * Select
 */
class Select extends AbstractMultiValueFormField
{

    /**
     * Displays option icons as table beneath the select.
     *
     * @var boolean
     * @see https://docs.typo3.org/typo3cms/TCAReference/Reference/Columns/Select/Index.html#showicontable
     */
    protected $showIconTable = false;


    /**
     * @var string
     */
    protected $foreignTableWhere = '';


    /**
     * @var string
     */
    protected $foreignTable = '';

    /**
     * @return array
     */
    public function buildConfiguration(): array
    {
        $configuration = parent::prepareConfiguration('select');
        $configuration['showIconTable'] = $this->getShowIconTable();
        $configuration['foreign_table'] = $this->getForeignTable();
        $configuration['foreign_table_where'] = $this->getForeignTableWhere();
        return $configuration;
    }

    /**
     * @return boolean
     */
    public function getShowIconTable()
    {
        return $this->showIconTable;
    }

    /**
     * @param boolean $showIconTable
     * @return Select
     */
    public function setShowIconTable($showIconTable)
    {
        $this->showIconTable = $showIconTable;
        return $this;
    }

    /**
     * @return string
     */
    public function getForeignTableWhere(): string
    {
        return $this->foreignTableWhere;
    }

    /**
     * @param string $foreignTableWhere
     */
    public function setForeignTableWhere(string $foreignTableWhere)
    {
        $this->foreignTableWhere = $foreignTableWhere;
    }

    /**
     * @return string
     */
    public function getForeignTable(): string
    {
        return $this->foreignTable;
    }

    /**
     * @param string $foreignTable
     */
    public function setForeignTable(string $foreignTable)
    {
        $this->foreignTable = $foreignTable;
    }

}
