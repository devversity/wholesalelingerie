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

namespace Bss\Paymentshipping\Block\Adminhtml\Paymentshipping;

class Index extends \Magento\Backend\Block\Template
{
    /**
     * @var \Magento\Framework\App\RequestInterface|string
     */
    protected $type = '';

    /**
     * @var array
     */
    protected $visibility = [];

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Bss\Paymentshipping\Helper\Data
     */
    protected $dataHelper;

    /**
     * @var \Magento\Framework\Registry|null
     */
    protected $coreRegistry = null;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface|null
     */
    protected $storeManager = null;

    /**
     * @var \Magento\Store\Model\GroupFactory
     */
    protected $groupFactory;

    /**
     * Index constructor.
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\App\Action\Context $appContext
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Store\Model\GroupFactory $groupFactory
     * @param \Bss\Paymentshipping\Helper\Data $dataHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\App\Action\Context $appContext,
        \Magento\Framework\Registry $registry,
        \Magento\Store\Model\GroupFactory $groupFactory,
        \Bss\Paymentshipping\Helper\Data $dataHelper,
        array $data = []
    ) {
        $this->type = $appContext->getRequest();
        $this->request = $appContext->getRequest();
        $this->scopeConfig = $context->getScopeConfig();
        $this->coreRegistry = $registry;
        $this->storeManager = $context->getStoreManager();
        $this->groupFactory = $groupFactory;
        $this->dataHelper = $dataHelper;
        parent::__construct($context, $data);
    }

    /**
     *
     */
    protected function _prepareVisibility()
    {
        $collection = $this->dataHelper->getMethodsVisibility($this->type->getActionName(), $this->getCurrentWebsite());
        foreach ($collection as $method) {
            $this->visibility[$method->getMethod()] = explode(',', $method->getGroupIds());
        }
    }

    /**
     * @return string
     */
    public function getMethodsType()
    {
        return ucwords($this->type->getActionName());
    }

    /**
     * @return array
     */
    public function getMethods()
    {
        if ('payment' == $this->type->getActionName()) {
            $methods = $this->_getPaymentMethods();
        } elseif ('shipping' == $this->type->getActionName()) {
            $methods = $this->_getShippingMethods();
        }
        return $methods;
    }

    /**
     * @return string
     */
    public function getSaveUrl()
    {
        $params = ['_current' => 'true'];
        return $this->getUrl('*/*/save', $params);
    }

    /**
     * @param int|null $website
     * @return string
     */
    public function getWebsiteUrl($website = null)
    {
        if (!$website) {
            $websiteId = 1;
        } else {
            $websiteId = $website->getId();
        }
        return $this->getUrl('*/*/*', ['website_id' => $websiteId, '_current' => true]);
    }

    /**
     * @return \Magento\Store\Api\Data\WebsiteInterface[]
     */
    public function getWebsites()
    {
        $websites = $this->storeManager->getWebsites();
        return $websites;
    }

    /**
     * @return mixed
     */
    public function getCurrentWebsite()
    {
        $websiteId = $this->request->getParam('website_id', 1);
        return $websiteId;
    }

    /**
     * @return array
     */
    public function getCustomerGroups()
    {
        $groups = $this->dataHelper->getCustomerGroup();
        foreach ($groups as $eachGroup) {
            $option['value'] = $eachGroup->getCustomerGroupId();
            $option['label'] = $eachGroup->getCustomerGroupCode();
            $options[] = $option;
        }

        return $options;
    }

    /**
     * @param array $group
     * @param string $methodCode
     * @return bool
     */
    public function isGroupSelected($group, $methodCode)
    {
        $this->_prepareVisibility();
        if (isset($this->visibility[$methodCode]) && in_array($group['value'], $this->visibility[$methodCode])) {
            return true;
        }
        return false;
    }

    /**
     * @return array
     */
    protected function _getPaymentMethods()
    {
        $firstStoreViewIdCurrent = $this->getFirstStoreViewCurrent(null);
        $payments = $this->dataHelper->getActivePaymentMethods($firstStoreViewIdCurrent);
        $methods = [];
        foreach ($payments as $value) {
            $title = isset($value['title']) ? $value['title']. ' ('.$value['code'].')' : '('.$value['code'].')';
            if ($value['code'] == 'wps_express') {
                $methods['paypal_express'] = [
                    'title' => $title,
                    'value' => 'paypal_express'
                ];
            } else {
                $methods[$value['code']] = [
                    'title' => $title,
                    'value' => $value['code']
                ];
            }
        }
        return $methods;
    }

    /**
     * @param string $param
     * @return mixed
     */
    protected function getFirstStoreViewCurrent($param = 'code')
    {
        $groupId = $this->getCurrentWebsite();
        $sotresView = $this->groupFactory->create()->getCollection()->addFieldToFilter('website_id', $groupId);
        foreach ($sotresView as $storeView) {
            foreach ($storeView->getStores() as $myStore) {
                if ($param == 'code') {
                    return $myStore->getCode();
                } else {
                    return $myStore->getId();
                }
            }
        }
    }

    /**
     * @return array
     */
    protected function _getShippingMethods()
    {
        $firstStoreViewIdCurrent = $this->getFirstStoreViewCurrent();
        $scopCode = $this->getFirstStoreViewCurrent('code');
        $shipping = $this->dataHelper->getActiveShippingMethods($firstStoreViewIdCurrent);
        $methods = [];
        $shippingCodes = array_keys($shipping);
        foreach ($shippingCodes as $shippingCode) {
            $shippingTitle = $this->scopeConfig->getValue(
                'carriers/' . $shippingCode . '/title',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                $scopCode
            );
            $methods[$shippingCode] = [
                'title' => $shippingTitle,
                'value' => $shippingCode,
            ];
        }
        return $methods;
    }
}
