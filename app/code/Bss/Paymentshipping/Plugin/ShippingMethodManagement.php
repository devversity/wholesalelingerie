<?php
/**
 * BSS Commerce Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://bsscommerce.com/Bss-Commerce-License.txt
 *
 * @category   BSS
 * @package    Bss_Paymentshipping
 * @author     Extension Team
 * @copyright  Copyright (c) 2015-2016 BSS Commerce Co. ( http://bsscommerce.com )
 * @license    http://bsscommerce.com/Bss-Commerce-License.txt
 */

namespace Bss\Paymentshipping\Plugin;

class ShippingMethodManagement
{
    /**
     * @var \Bss\Paymentshipping\Helper\Data
     */
    protected $bssHelper;

    /**
     * Constructor
     *
     * @param \Bss\Paymentshipping\Helper\Data $bssHelper
     */
    public function __construct(
        \Bss\Paymentshipping\Helper\Data $bssHelper
    ) {
        $this->bssHelper = $bssHelper;
    }

    /**
     * @param \Magento\Quote\Model\Quote\Address $subject
     * @param array $shippingRates
     * @return mixed
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    public function afterGetGroupedAllShippingRates(\Magento\Quote\Model\Quote\Address $subject, $shippingRates)
    {
        $myHelperData = $this->bssHelper;
        foreach ($shippingRates as $methodCode => $method) {
            if (!$myHelperData->canUseMethod($methodCode, 'shipping')) {
                unset($shippingRates[$methodCode]);
            }
        }

        return $shippingRates;
    }
}
