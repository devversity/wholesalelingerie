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

use Magento\Quote\Model\Quote\Address\RateResult\Error;
use Magento\Quote\Model\Quote\Address\RateRequest;
use Lof\TableRateShipping\Model\ShippingmethodFactory;
use Magento\Catalog\Model\ProductFactory;
use Magento\Framework\Session\SessionManager;
use Magento\Quote\Model\Quote\Item\OptionFactory;
use Lof\TableRateShipping\Model\TableRateShippingFactory;
use \Magento\Framework\Unserialize\Unserialize;

class Carrier extends \Magento\Shipping\Model\Carrier\AbstractCarrier implements
    \Magento\Shipping\Model\Carrier\CarrierInterface
{
    /**
     * Code of the carrier.
     *
     * @var string
     */
    const CODE = 'loftablerateshipping';
    /**
     * Code of the carrier.
     *
     * @var string
     */
    protected $_code = self::CODE;
    /**
     * Rate request data.
     *
     * @var \Magento\Quote\Model\Quote\Address\RateRequest|null
     */
    protected $_request;
    /**
     * @var \Magento\Catalog\Model\Product
     */
    protected $_productFactory;
    /**
     * Rate result data.
     *
     * @var Result|null
     */
    protected $_result;
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;
    /**
     * @var array
     */
    protected $_errors = [];

    protected $_isFixed = true;
    /**
     * @var \Magento\Shipping\Model\Rate\ResultFactory
     */
    protected $_rateResultFactory;
    /**
     * @var \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory
     */
    protected $_rateMethodFactory;
    /**
     * Raw rate request data.
     *
     * @var \Magento\Framework\DataObject|null
     */
    protected $_rawRequest = null;
    /**
     * @var Lof\TableRateShipping\Model\ShippingmethodFactory
     */
    protected $_mpshippingMethod;
    /**
     * @var Magento\Framework\Session\SessionManager
     */
    protected $_coreSession;
    /**
     * @var OptionFactory
     */
    protected $_itemOptionModel;
    /**
     * @var Lof\Marketplace\Model\ProductFactory
     */
    protected $_mpProductFactory;
    /**
     * @var TableRateShippingFactory
     */
    protected $_mpShippingModel;
    /**
     * @var Unserialize
     */
    protected $_unserialize;
     /**
     * @var Unserialize
     */
    protected $collectionFactory;
    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface          $scopeConfig
     * @param \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory  $rateErrorFactory
     * @param \Psr\Log\LoggerInterface                                    $logger
     * @param \Magento\Shipping\Model\Rate\ResultFactory                  $rateResultFactory
     * @param \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory
     * @param ProductFactory                                              $productFactory
     * @param \Magento\Framework\ObjectManagerInterface                   $objectManager
     * @param ShippingmethodFactory                                     $shippingmethodFactory
     * @param SessionManager                                              $coreSession
     * @param OptionFactory                                               $itemOptionModel
     * @param TableRateShippingFactory                                           $mpshippingModel
     * @param array                                                       $data
     */
    
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory,
        \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory,
        ProductFactory $productFactory,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        ShippingmethodFactory $shippingmethodFactory,
        SessionManager $coreSession,
        OptionFactory $itemOptionModel,
        ShippingFactory $mpshippingModel,
        Unserialize $unserialize,
        \Magento\Framework\Data\CollectionFactory $collectionFactory,
        array $data = []
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->_productFactory = $productFactory;
        $this->_objectManager = $objectManager;
        $this->_rateResultFactory = $rateResultFactory;
        $this->_rateMethodFactory = $rateMethodFactory;
        $this->_mpshippingMethod = $shippingmethodFactory;
        $this->_coreSession = $coreSession;
        $this->_itemOptionModel = $itemOptionModel;
        $this->_mpShippingModel = $mpshippingModel;
        $this->_unserialize = $unserialize;
        parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);
    }
    /**
     * Collect and get rates.
     *
     * @param RateRequest $request
     *
     * @return \Magento\Quote\Model\Quote\Address\RateResult\Error|bool|Result
     */
    public function collectRates(RateRequest $request)
    {
        $this->setRequest($request);
        $shippingpricedetail = $this->getShippingPricedetail($this->_rawRequest);
       
        return $shippingpricedetail;
    }
    /**
     * @param \Magento\Framework\DataObject|null $request
     * @return $this
     * @api
     */
    public function setRawRequest($request)
    {
        $this->_rawRequest = $request;

        return $this;
    }
    /**
     * Prepare and set request to this instance.
     *
     * @param \Magento\Quote\Model\Quote\Address\RateRequest $request
     *
     * @return $this
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function setRequest(\Magento\Quote\Model\Quote\Address\RateRequest $request)
    {
        $this->_request = $request;
        $requestData = new \Magento\Framework\DataObject();
        $product = $this->_productFactory->create();
        $mpassignproductId = 0;
        $shippingdetail = [];
        $partner = 0;
        $handling = 0;
        foreach ($request->getAllItems() as $item) {
            $proid = $item->getProductId();
            $options = $item->getProductOptions();
            $mpassignproductId = 0;
            $itemOption = $this->_itemOptionModel->create()
                ->getCollection()
                ->addFieldToFilter('item_id', ['eq' => $item->getId()])
                ->addFieldToFilter('code', ['eq' => 'info_buyRequest']);
            
            if ($item->getProduct()->isVirtual() || $item->getParentItem()) {
                continue;
            }

            $weight = $this->calculateWeightForProduct($item);
            if (empty($shippingdetail)) {
                array_push(
                    $shippingdetail,
                    [
                        'seller_id' => $partner,
                        'items_weight' => $weight,
                        'product_name' => $item->getName(),
                        'item_id' => $item->getId(),
                    ]
                );
            } else {
                $shipinfoflag = true;
                $index = 0;
                foreach ($shippingdetail as $itemship) {
                    if ($itemship['seller_id'] == $partner) {
                        $itemship['items_weight'] = $itemship['items_weight'] + $weight;
                        $itemship['product_name'] = $itemship['product_name'].','.$item->getName();
                        $itemship['item_id'] = $itemship['item_id'].','.$item->getId();
                        $shippingdetail[$index] = $itemship;
                        $shipinfoflag = false;
                    }
                    ++$index;
                }
                if ($shipinfoflag == true) {
                    array_push(
                        $shippingdetail,
                        [
                            'seller_id' => $partner,
                            'items_weight' => $weight,
                            'product_name' => $item->getName(),
                            'item_id' => $item->getId(),
                        ]
                    );
                }
            }
        }
       
        if ($request->getShippingDetails()) {
            $shippingdetail = $request->getShippingDetails();
        }
        $requestData->setShippingDetails($shippingdetail);

        $requestData->setDestCountryId($request->getDestCountryId());

        if ($request->getDestPostcode()) {
            $requestData->setDestPostal(str_replace('-', '', $request->getDestPostcode()));
        }
       
        $this->setRawRequest($requestData);

        return $this;
    }
    /**
     * Calculate the rate according to Tabel Rate shipping defined by the sellers.
     *
     * @return Result
     */
    public function getShippingPricedetail(\Magento\Framework\DataObject $request)
    {
        $requestData = $request;
        $submethod = [];
        $shippinginfo = [];
        $msg = '';
        $handling = 0;
        $totalPriceArr = [];
        $flag = false;
        $check = false;
        $returnError = false;
       
        foreach ($requestData->getShippingDetails() as $shipdetail) {
            $thisMsg = false;
            $priceArr = [];
            $price = 0;
            $shipping = $this->getShippingPriceRates($shipdetail, $requestData);

            if ($shipping->getSize() != 0) {
                $priceArr = $this->getPriceArrForRate($shipping);
            } else {
                return;
            }

            if (!empty($totalPriceArr)) {
                foreach ($priceArr as $method => $price) {
                    if (array_key_exists($method, $totalPriceArr)) {
                        $check = true;
                        $totalPriceArr[$method] = $totalPriceArr[$method] + $priceArr[$method];
                    } else {
                        $thisMsg = true;
                        unset($priceArr[$method]);
                    }
                    $flag = $check == true ? false : true;
                }
            } else {
                $totalPriceArr = $priceArr;
            }
            if (!empty($priceArr)) {
                foreach ($totalPriceArr as $method => $price) {
                    if (!array_key_exists($method, $priceArr)) {
                        unset($totalPriceArr[$method]);
                    }
                }
            } else {
                $totalPriceArr = [];
                $flag = true;
            }
            if ($flag) {
                if ($thisMsg) {
                    $msg = $this->getErrorMsg($msg, $shipdetail);
                }
                $returnError = true;
                $debugData['result'] = ['error' => 1, 'errormsg'=>$msg];
            }
            $submethod = $this->getSubMethodsForRate($priceArr);
            $handling = $handling + $price;
            array_push(
                $shippinginfo,
                [
                    'seller_id' => $shipdetail['seller_id'],
                    'methodcode' => $this->_code,
                    'shipping_ammount' => $price,
                    'product_name' => $shipdetail['product_name'],
                    'submethod' => $submethod,
                    'item_ids' => $shipdetail['item_id'],
                ]
            );
        }
        if ($returnError) {
            return $this->_parseXmlResponse($debugData);
        }
        $totalpric = ['totalprice' => $totalPriceArr, 'costarr' => $priceArr];
        $result = ['handlingfee' => $totalpric, 'shippinginfo' => $shippinginfo, 'error' => 0];
        $shippingAll = $this->_coreSession->getShippingInfo();
        $shippingAll[$this->_code] = $result['shippinginfo'];
        $this->_coreSession->setShippingInfo($shippingAll);
        return $this->_parseXmlResponse($totalpric);
    }
    /**
     * @return array
     */
    public function getAllowedMethods()
    {
        return ['loftablerateshipping' => $this->getConfigData('name')];
    }

    public function getShipMethodNameById($shipMethodId)
    {
        $methodName = '';
        $shippingMethodModel = $this->_mpshippingMethod->create()
            ->load($shipMethodId);
        $methodName = $shippingMethodModel->getMethodName();
        return $methodName;
    }
    protected function _parseXmlResponse($response)
    {
        $result = $this->_rateResultFactory->create();
        if (array_key_exists('result', $response) && $response['result']['error'] !== '') {
            $this->_errors[$this->_code] = $response['result']['errormsg'];
            $errors = explode('<br>', $response['result']['errormsg']);
            $error = $this->_rateErrorFactory->create();
            $error->setCarrier($this->_code);
            $error->setCarrierTitle($this->getConfigData('title'));
            foreach ($errors as $key => $value) {
                $errorMsg[] = $value;
            }
            $error->setErrorMessage($errorMsg);
            return $error;
            // Display error message if there
        } else {
            $totalPriceArr = $response['totalprice'];
            $costArr = $response['costarr'];
              
            foreach ($totalPriceArr as $method => $price) {
                if($method) {
                    $rate = $this->_rateMethodFactory->create();
                    $rate->setCarrier($this->_code);
                    $rate->setCarrierTitle($this->getConfigData('title'));
                    $rate->setMethod($method);
                    $rate->setMethodTitle($method);
                    $rate->setCost($costArr[$method]);
                    $rate->setPrice($price);
                    $result->append($rate);
                }
            }
        }
       
        return $result;
    }
    

    public function calculateWeightForProduct($item)
    {
        $childWeight = 0;
        if ($item->getHasChildren()) {
            $_product = $this->_productFactory
                ->create()
                ->load($item->getProductId());
            if ($_product->getTypeId() == 'bundle') {
                foreach ($item->getChildren() as $child) {
                    $productWeight = $this->_productFactory
                        ->create()
                        ->load($child->getProductId())
                        ->getWeight();
                    $childWeight += $productWeight * $child->getQty();
                }
                $weight = $childWeight * $item->getQty();
            } elseif ($_product->getTypeId() == 'configurable') {
                foreach ($item->getChildren() as $child) {
                    $productWeight = $this->_productFactory->create()
                        ->load($child->getProductId())
                        ->getWeight();
                    $weight = $productWeight * $item->getQty();
                }
            }
        } else {
            $productWeight = $this->_productFactory->create()
                ->load($item->getProductId())
                ->getWeight();
            $weight = $productWeight * $item->getQty();
        }
        return $weight;
    }

    public function getShippingcollectionAccordingToDetails($countryId, $sellerId, $postalCode, $weight)
    {
        $shipping = $this->_mpShippingModel->create()
                ->getCollection()
                ->addFieldToFilter('dest_country_id', ['eq' => $countryId])
                ->addFieldToFilter('dest_zip', [['lteq' => $postalCode],['like'=>'%*%']])
                ->addFieldToFilter('dest_zip_to', [['gteq' => $postalCode],['like'=>'%*%']])
                ->addFieldToFilter('weight_from', ['lteq' => $weight])
                ->addFieldToFilter('weight_to', ['gteq' => $weight]);    
        return $shipping;
    }

    public function getShippingcollectionAccordingastrik($countryId, $sellerId, $postalCode, $weight)
    {
        $shipping = $this->_mpShippingModel->create()
                ->getCollection()
                ->addFieldToFilter('dest_country_id', ['eq' => $countryId])
                ->addFieldToFilter('partner_id', ['eq' => $sellerId])
                ->addFieldToFilter('dest_zip', [['lteq' => $postalCode],['like'=>'%*%']])
                ->addFieldToFilter('dest_zip_to', [['gteq' => $postalCode],['like'=>'%*%']])
                ->addFieldToFilter('weight_from', ['lteq' => $weight])
                ->addFieldToFilter('weight_to', ['gteq' => $weight]);
        return $shipping;
    }

    public function getShippingPriceRates($shipdetail, $requestData)
    {
        $shipping = $this->getShippingcollectionAccordingToDetails(
            $requestData->getDestCountryId(),
            $shipdetail['seller_id'],
            (int)($requestData->getDestPostal()),
            $shipdetail['items_weight']
        );
        if ($shipping->getSize() == 0) {
            $shipping = $this->getShippingcollectionAccordingastrik(
                $requestData->getDestCountryId(),
                $shipdetail['seller_id'],
                '*',
                $shipdetail['items_weight']
            );
        }
        if ($shipping->getSize() == 0) {
            if ($this->getConfigData('allowadmin')) {
                $price = 0;
                $shipping = $this->getShippingcollectionAccordingToDetails(
                    $requestData->getDestCountryId(),
                    0,
                    (int)($requestData->getDestPostal()),
                    $shipdetail['items_weight']
                );
                if ($shipping->getSize() == 0) {
                    $shipping = $this->getShippingcollectionAccordingastrik(
                        $requestData->getDestCountryId(),
                        0,
                        '*',
                        $shipdetail['items_weight']
                    );
                }
            }
        }
        
        return $shipping;
    }

    public function getPriceArrForRate($shipping)
    {
        $priceArr = [];
        foreach ($shipping as $ship) {
            $price = floatval($ship->getPrice());
            $shipMethodId = $ship->getShippingMethodId();
            if ($shipMethodId) {
                $shipMethodName = $this->getShipMethodNameById($shipMethodId);
            } else {
                $shipMethodName = $this->getConfigData('title');
            }
            $priceArr[$shipMethodName] = $price;
        }
        return $priceArr;
    }

    public function getSubMethodsForRate($priceArr)
    {
        $submethod = [];
        if (!empty($priceArr)) {
            foreach ($priceArr as $index => $price) {
                $submethod[$index] = [
                    'method' => $index.' ('.$this->getConfigData('title').')',
                    'cost' => $price,
                    'base_amount' => $price,
                    'error' => 0,
                ];
            }
        }
        return $submethod;
    }

    public function getErrorMsg($msg, $shipdetail)
    {
        $thisMsg = __(
            'Product %1 do not provide shipping service to your location.',
            $shipdetail['product_name']
        );
        if ($msg=='') {
            $msg = $thisMsg;
        } else {
            $msg = $msg."<br>".$thisMsg;
        }
        return $msg;
    }
}
