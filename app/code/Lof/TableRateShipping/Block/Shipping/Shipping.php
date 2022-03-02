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


namespace Lof\TableRateShipping\Block\Shipping;

use Magento\Catalog\Model\Product;
use Magento\Customer\Model\Customer;
use Magento\Customer\Model\Session;
use Magento\Eav\Model\Entity\Collection\AbstractCollection;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Catalog\Block\Product\AbstractProduct;
use Lof\TableRateShipping\Model\ShippingmethodFactory;
use Magento\Directory\Model\ResourceModel\Country;
use Lof\TableRateShipping\Model\ShippingFactory;

class Shipping extends AbstractProduct
{
    /**
     * @var \Magento\Framework\Data\Helper\PostHelper
     */
    protected $_postDataHelper;
    /**
     * @var \Magento\Framework\Url\Helper\Data
     */
    protected $_urlHelper;
    /**
     * @var \Magento\Customer\Model\Customer
     */
    protected $_customer;
    /**
     * @var \Magento\Customer\Model\Customer
     */
    protected $_session;
    /**
     * @var Lof\TableRateShipping\Model\ShippingmethodFactory
     */
    protected $_mpshippingMethod;
    /**
     * @var \Magento\Directory\Model\ResourceModel\Country\CollectionFactory
     */
    protected $_countryCollectionFactory;
    /**
     * @var Lof\TableRateShipping\Model\ShippingFactory
     */
    protected $_mpshippingModel;

    protected $request;

    /**
     * @param \Magento\Catalog\Block\Product\Context    $context
     * @param \Magento\Framework\Data\Helper\PostHelper $postDataHelper
     * @param \Magento\Framework\Url\Helper\Data        $urlHelper
     * @param Customer                                  $customer
     * @param \Magento\Customer\Model\Session           $session
     * @param ShippingmethodFactory                   $shippingmethodFactory
     * @param Country\CollectionFactory                 $countryCollectionFactory
     * @param ShippingFactory                         $mpshippingModel
     * @param array                                     $data
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Data\Helper\PostHelper $postDataHelper,
        \Magento\Framework\Url\Helper\Data $urlHelper,
        Customer $customer,
        \Magento\Customer\Model\Session $session,
        ShippingmethodFactory $shippingmethodFactory,
        Country\CollectionFactory $countryCollectionFactory,
        ShippingFactory $mpshippingModel,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_postDataHelper = $postDataHelper;
        $this->_urlHelper = $urlHelper;
        $this->_customer = $customer;
        $this->_session = $session;
        $this->request =  $context->getRequest();
        $this->_mpshippingMethod = $shippingmethodFactory;
        $this->_countryCollectionFactory = $countryCollectionFactory;
        $this->_mpshippingModel = $mpshippingModel;
    }
     public function getShippingId() {
        $path = trim($this->request->getPathInfo(), '/');
        $params = explode('/', $path);
        return end($params);
    }
    /**
     * @return int
     */
    public function getCustomerId()
    {
        return $this->_session->getCustomerId();
    }
    public function getShipping($shipping_id) {
         $querydata = $this->_mpshippingModel->create()
            ->getCollection()
            ->addFieldToFilter(
                'lofshipping_id',$shipping_id
            );
        return $querydata;
    }
    /**
     * @param  int $partnerId
     * @return \Lof\TableRateShipping\Model\Shipping
     */
    public function getShippingCollection($partnerId = null)
    {
        $querydata = $this->_mpshippingModel->create()
            ->getCollection()
            ->addFieldToFilter(
                'partner_id',
                ['eq' => $partnerId]
            );
        return $querydata;
    }

    public function getShippingMethodCollection()
    {
        $shippingMethodCollection = $this->_mpshippingMethod
            ->create()
            ->getCollection();
        return $shippingMethodCollection;
    }
    public function getShippingMethod()
    {
        $shippingMethodCollection = $this->_mpshippingMethod
            ->create();
        return $shippingMethodCollection;
    }
    public function getShippingforShippingMethod($methodId, $partnerId)
    {
        $querydata = $this->_mpshippingModel
            ->create()
            ->getCollection()
            ->addFieldToFilter(
                'shipping_method_id',
                ['eq' => $methodId]
            )
            ->addFieldToFilter(
                'partner_id',
                ['eq' => $partnerId]
            );
        return $querydata;
    }

    public function getShippingMethodName($shippingMethodId)
    {
        $methodName = '';
        $shippingMethodModel = $this->_mpshippingMethod->create()
            ->getCollection()
            ->addFieldToFilter('entity_id', $shippingMethodId);
        foreach ($shippingMethodModel as $shippingMethod) {
            $methodName = $shippingMethod->getMethodName();
        }
        return $methodName;
    }
    public function getCountryOptionArray()
    {
        $options = $this->getCountryCollection()
            ->setForegroundCountries($this->getTopDestinations())
            ->toOptionArray();
        $options[0]['label'] = 'Please select Country';

        return $options;
    }
    public function getCountryCollection()
    {
        $collection = $this->_countryCollectionFactory
            ->create()
            ->loadByStore();
        return $collection;
    }
    /**
     * Retrieve list of top destinations countries.
     *
     * @return array
     */
    protected function getTopDestinations()
    {
        $destinations = (string) $this->_scopeConfig->getValue(
            'general/country/destinations',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        return !empty($destinations) ? explode(',', $destinations) : [];
    }
}
