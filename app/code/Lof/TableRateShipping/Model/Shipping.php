<?php
/**
 * Landofcoder
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Landofcoder.com license that is
 * available through the world-wide-web at this URL:
 * http://landofcoder.com/license
 * 
 * DISCLAIMER
 * 
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 * 
 * @category   Landofcoder
 * @package    Lof_TableRateShipping
 * @copyright  Copyright (c) 2017 Landofcoder (http://www.landofcoder.com/)
 * @license    http://www.landofcoder.com/LICENSE-1.0.html
 */

namespace Lof\TableRateShipping\Model;

use Lof\TableRateShipping\Api\Data\TableRateShippingInterface;
use Magento\Framework\DataObject\IdentityInterface;
use \Magento\Framework\Model\AbstractModel;

class Shipping extends AbstractModel implements TableRateShippingInterface, IdentityInterface
{
    /**
     * CMS page cache tag
     */
    const CACHE_TAG = 'loftablerateshipping';

    /**
     * @var string
     */
    protected $_cacheTag = 'loftablerateshipping';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'loftablerateshipping';

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Lof\TableRateShipping\Model\ResourceModel\Shipping');
    }
    /**
     * Return unique ID(s) for each object in system
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getLofshippingId()];
    }
    /**
     * Get ID
     *
     * @return int|null
     */
    public function getLofshippingId()
    {
        return $this->getData(self::LOFMPSHIPPING_ID);
    }
    public function setLofshippingId($id)
    {
        return $this->setData(self::LOFMPSHIPPING_ID, $id);
    }
}
