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

namespace Lof\TableRateShipping\Controller\Adminhtml\Shipping;

use Lof\TableRateShipping\Controller\Adminhtml\Shipping as ShippingController;
use Magento\Framework\Controller\ResultFactory;

class Index extends ShippingController
{
    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('Lof_TableRateShipping::mpshipping');
        $resultPage->getConfig()->getTitle()->prepend(__(' Table Rate Shipping Manager'));
        $resultPage->addBreadcrumb(
            __(' Table Rate Shipping Manager'),
            __(' Table Rate Shipping Manager')
        );
       /* $resultPage->addContent(
            $resultPage
            ->getLayout()
            ->createBlock(
                'Lof\TableRateShipping\Block\Adminhtml\Shipping\Edit'
            )
        );
        $resultPage->addLeft(
            $resultPage
            ->getLayout()
            ->createBlock(
                'Lof\TableRateShipping\Block\Adminhtml\Shipping\Edit\Tabs'
            )
        );*/
        return $resultPage;
    }
}
