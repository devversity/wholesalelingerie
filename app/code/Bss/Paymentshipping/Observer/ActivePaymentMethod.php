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

namespace Bss\Paymentshipping\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;

class ActivePaymentMethod implements ObserverInterface
{
    /**
     * @var \Bss\Paymentshipping\Helper\Data
     */
    protected $bssHelper;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        \Bss\Paymentshipping\Helper\Data $bssHelper
    ) {
        $this->bssHelper = $bssHelper;
    }

    /**
     * @param EventObserver $observer
     */
    public function execute(EventObserver $observer)
    {
        $result = $observer->getResult();
        $method = $observer->getMethodInstance()->getCode();
        $myHelperData = $this->bssHelper;

        if (!$myHelperData->canUseMethod($method, 'payment')) {
            $result->setData('is_available', false);
        }
    }
}
