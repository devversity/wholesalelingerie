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

use Magento\Backend\App\Action;
use Lof\TableRateShipping\Model\ShippingmethodFactory;
use Lof\TableRateShipping\Model\ShippingFactory;
use Magento\MediaStorage\Model\File\UploaderFactory;

class Save extends \Magento\Backend\App\Action
{
    /**
     * Core registry.
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_resultPageFactory;
    /**
     * @var Lof\TableRateShipping\Model\ShippingmethodFactory
     */
    protected $_mpshippingMethod;
    /**
     * @var Lof\TableRateShipping\Model\Shipping
     */
    protected $_mpshipping;
    /**
     * @var Magento\MediaStorage\Model\File\UploaderFactory
     */
    protected $_fileUploader;
    /**
     * @var \Magento\Framework\File\Csv
     */
    protected $_csvReader;

    /**
     * @param Action\Context                             $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Registry                $registry
     */
    public function __construct(
        Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        ShippingmethodFactory $shippingmethodFactory,
        ShippingFactory $mpshipping,
        UploaderFactory $fileUploader,
        \Magento\Framework\File\Csv $csvReader
    ) {
        parent::__construct($context);
        $this->_resultPageFactory = $resultPageFactory;
        $this->_mpshippingMethod = $shippingmethodFactory;
        $this->_mpshipping = $mpshipping;
        $this->_fileUploader = $fileUploader;
        $this->_csvReader = $csvReader;
    }

    /**
     * Check for is allowed.
     *
     * @return bool
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
    
        if ($this->getRequest()->isPost()) {
            try {
              
                if(isset($_FILES['import_file'])) {
                    
                    if (!$this->_formKeyValidator->validate($this->getRequest())) {
                        return $this->resultRedirectFactory->create()->setPath('*/*/index');
                    }
                  
                    $uploader = $this->_fileUploader->create(
                        ['fileId' => 'import_file']
                    );
                     
                    $result = $uploader->validateFile();
                   
                    $file = $result['tmp_name'];
                    $fileNameArray = explode('.', $result['name']);
                    
                    $ext = end($fileNameArray);
                    if ($file != '' && $ext == 'csv') {
                        $csvFileData = $this->_csvReader->getData($file);
                        $partnerid = 0;
                        $count = 0;
           
                        foreach ($csvFileData as $key => $rowData) {
                            if (count($rowData) < 9 && $count == 0) {
                                $this->messageManager->addError(__('Csv file is not a valid file!'));
                                return $this->resultRedirectFactory->create()->setPath('*/*/index');
                            }
                            if ($rowData[0] == '' ||
                                $rowData[1] == '' ||
                                $rowData[2] == '' ||
                                $rowData[3] == '' ||
                                $count == 0
                            ) {
                                ++$count;
                                continue;
                            }
                            $shippingMethodId = $this->calculateShippingMethodId($rowData[7]);
                            $temp = [];
                            $temp['dest_country_id'] = $rowData[0];
                            $temp['dest_region_id'] = $rowData[1];
                            $temp['dest_zip'] = $rowData[2];
                            $temp['dest_zip_to'] = $rowData[3];
                            $temp['price'] = $rowData[4];
                            $temp['weight_from'] = $rowData[5];
                            $temp['weight_to'] = $rowData[6];
                            $temp['shipping_method_id'] = $shippingMethodId;
                            $temp['partner_id'] = $rowData[8];
                            $partnerid = $rowData[8];
                            $this->addDataToCollection($temp, $rowData, $shippingMethodId, $partnerid);
                        }
                        if (($count - 1) > 1) {
                            $this->messageManager->addNotice(__('Some rows are not valid!'));
                        }
                        if (($count - 1) <= 1) {
                            $this->messageManager
                                ->addSuccess(
                                    __('Your shipping detail has been successfully Saved')
                                );
                        }
                        return $this->resultRedirectFactory->create()->setPath('*/*/index');
                    } else {
                        $this->messageManager->addError(__('Please upload Csv file'));
                    }
                } else {
                    $params = $data;
                    $partnerid = 0;
                    $shippingMethodId = $this->getShippingNameById($params['shipping_method']);

                    if ($shippingMethodId==0) {
                        $mpshippingMethod = $this->_mpshippingMethod->create();
                        $mpshippingMethod->setMethodName($params['shipping_method']);
                        $savedMethod = $mpshippingMethod->save();
                        $shippingMethodId = $savedMethod->getEntityId();
                    }
                    
                    $shippingModel = $this->_mpshipping->create();
                   
                    if (isset($params['lofshipping_id'])) {
                        $id = $params['lofshipping_id'];
                        $shippingModel->load($id);
                        $partnerid = $shippingModel->getData('partner_id');
                        
                        if ($id != $shippingModel->getId()) {
                            throw new \Magento\Framework\Exception\LocalizedException(__('The wrong shipping is specified.'));
                        }
                        $temp = [
                        'lofshipping_id' => $params['lofshipping_id'],
                        'dest_country_id' => $params['dest_country_id'],
                        'dest_region_id' => $params['dest_region_id'],
                        'dest_zip' => $params['dest_zip'],
                        'dest_zip_to' => $params['dest_zip_to'],
                        'price' => $params['price'],
                        'weight_from' => $params['weight_from'],
                        'weight_to' => $params['weight_to'],
                        'shipping_method_id' => $shippingMethodId,
                        'partner_id' => $partnerid,
                        ];
                    } else {
                       $temp = [
                        'dest_country_id' => $params['dest_country_id'],
                        'dest_region_id' => $params['dest_region_id'],
                        'dest_zip' => $params['dest_zip'],
                        'dest_zip_to' => $params['dest_zip_to'],
                        'price' => $params['price'],
                        'weight_from' => $params['weight_from'],
                        'weight_to' => $params['weight_to'],
                        'shipping_method_id' => $shippingMethodId,
                        'partner_id' => $partnerid,
                        ]; 
                    }
                   
                    
                       
                    
                    $shippingCollection = $this->_mpshipping->create()
                        ->getCollection()
                        ->addFieldToFilter('partner_id', $partnerid)
                        ->addFieldToFilter('dest_country_id', $params['dest_country_id'])
                        ->addFieldToFilter('dest_region_id', $params['dest_region_id'])
                        ->addFieldToFilter('dest_zip', $params['dest_zip'])
                        ->addFieldToFilter('dest_zip_to', $params['dest_zip_to'])
                        ->addFieldToFilter('weight_from', $params['weight_from'])
                        ->addFieldToFilter('weight_to', $params['weight_to'])
                        ->addFieldToFilter('shipping_method_id', $shippingMethodId);
                  
                    if ($shippingCollection->getsize() > 0) {
                        foreach ($shippingCollection as $data) {
                            $rowId = $data->getLofshippingId();
                            $dataArray = ['price' => $params['price']];
                            
                            $shippingModel->addData($dataArray);
                            $shippingModel->setLofshippingId($rowId)->save();
                        }
                    } else {
                        //$shippingModel = $this->_mpshipping->create();
                        $shippingModel->setData($temp);
                        $shippingModel->save();
        
                    }
                    $this->messageManager->addSuccess(__('Your shipping detail has been successfully Saved'));
                    return $this->resultRedirectFactory->create()->setPath('loftablerateshipping/shipping');
                }
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
        return $this->resultRedirectFactory->create()->setPath('*/*/index');
    }
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Lof_TableRateShipping::mpshipping');
    }
    public function getShippingNameById($shippingMethodName)
    {
        $entityId = 0;
        $shippingMethodModel = $this->_mpshippingMethod->create()
            ->getCollection()
            ->addFieldToFilter('method_name', $shippingMethodName);
        foreach ($shippingMethodModel as $shippingMethod) {
            $entityId = $shippingMethod->getEntityId();
        }
        return $entityId;
    }

    public function addDataToCollection($temp, $rowData, $shippingMethodId, $partnerid)
    {
        $collection = $this->_mpshipping->create()
            ->getCollection()
            ->addFieldToFilter('weight_to', $rowData[6])
            ->addFieldToFilter('dest_zip_to', $rowData[3])
            ->addFieldToFilter('dest_country_id', $rowData[0])
            ->addFieldToFilter('partner_id', $partnerid)
            ->addFieldToFilter('dest_region_id', $rowData[1])
            ->addFieldToFilter('dest_zip', $rowData[2])
            ->addFieldToFilter('weight_from', $rowData[5])
            ->addFieldToFilter('shipping_method_id', $shippingMethodId);

        if ($collection->getSize() > 0) {
            foreach ($collection as $data) {
                $rowId = $data->getLofshippingId();
                $dataArray = ['price' => $rowData[4]];
                $model = $this->_mpshipping->create();
                $shippingModel = $model->load($rowId)->addData($dataArray);
                $shippingModel->setLofshippingId($rowId)->save();
            }
        } else {
            $shippingModel = $this->_mpshipping->create();
            $shippingModel->setData($temp)->save();
        }
    }

    public function calculateShippingMethodId($sellerId)
    {
        $shippingMethodId = $this->getShippingNameById($sellerId);
        if ($shippingMethodId==0) {
            $mpshippingMethod = $this->_mpshippingMethod->create();
            $mpshippingMethod->setMethodName($sellerId);
            $savedMethod = $mpshippingMethod->save();
            $shippingMethodId = $savedMethod->getEntityId();
        }
        return $shippingMethodId;
    }
}
