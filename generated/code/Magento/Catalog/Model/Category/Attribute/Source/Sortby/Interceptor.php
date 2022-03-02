<?php
namespace Magento\Catalog\Model\Category\Attribute\Source\Sortby;

/**
 * Interceptor class for @see \Magento\Catalog\Model\Category\Attribute\Source\Sortby
 */
class Interceptor extends \Magento\Catalog\Model\Category\Attribute\Source\Sortby implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Catalog\Model\Config $catalogConfig)
    {
        $this->___init();
        parent::__construct($catalogConfig);
    }

    /**
     * {@inheritdoc}
     */
    public function getAllOptions()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getAllOptions');
        if (!$pluginInfo) {
            return parent::getAllOptions();
        } else {
            return $this->___callPlugins('getAllOptions', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setAttribute($attribute)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setAttribute');
        if (!$pluginInfo) {
            return parent::setAttribute($attribute);
        } else {
            return $this->___callPlugins('setAttribute', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAttribute()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getAttribute');
        if (!$pluginInfo) {
            return parent::getAttribute();
        } else {
            return $this->___callPlugins('getAttribute', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getOptionText($value)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getOptionText');
        if (!$pluginInfo) {
            return parent::getOptionText($value);
        } else {
            return $this->___callPlugins('getOptionText', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getOptionId($value)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getOptionId');
        if (!$pluginInfo) {
            return parent::getOptionId($value);
        } else {
            return $this->___callPlugins('getOptionId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addValueSortToCollection($collection, $dir = 'DESC')
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'addValueSortToCollection');
        if (!$pluginInfo) {
            return parent::addValueSortToCollection($collection, $dir);
        } else {
            return $this->___callPlugins('addValueSortToCollection', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getFlatColumns()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getFlatColumns');
        if (!$pluginInfo) {
            return parent::getFlatColumns();
        } else {
            return $this->___callPlugins('getFlatColumns', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getFlatIndexes()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getFlatIndexes');
        if (!$pluginInfo) {
            return parent::getFlatIndexes();
        } else {
            return $this->___callPlugins('getFlatIndexes', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getFlatUpdateSelect($store)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getFlatUpdateSelect');
        if (!$pluginInfo) {
            return parent::getFlatUpdateSelect($store);
        } else {
            return $this->___callPlugins('getFlatUpdateSelect', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getIndexOptionText($value)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getIndexOptionText');
        if (!$pluginInfo) {
            return parent::getIndexOptionText($value);
        } else {
            return $this->___callPlugins('getIndexOptionText', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'toOptionArray');
        if (!$pluginInfo) {
            return parent::toOptionArray();
        } else {
            return $this->___callPlugins('toOptionArray', func_get_args(), $pluginInfo);
        }
    }
}
