<?php
/**
 *
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @copyright   Copyright (c) 2014 X.commerce, Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
namespace Magento\Shipping\Controller\Adminhtml\Order\Shipment;

use \Magento\Backend\App\Action;

class RemoveTrack extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Shipping\Controller\Adminhtml\Order\ShipmentLoader
     */
    protected $shipmentLoader;

    /**
     * @param Action\Context $context
     * @param \Magento\Shipping\Controller\Adminhtml\Order\ShipmentLoader $shipmentLoader
     */
    public function __construct(
        Action\Context $context,
        \Magento\Shipping\Controller\Adminhtml\Order\ShipmentLoader $shipmentLoader
    ) {
        $this->shipmentLoader = $shipmentLoader;
        parent::__construct($context);
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magento_Sales::shipment');
    }

    /**
     * Remove tracking number from shipment
     *
     * @return void
     */
    public function execute()
    {
        $trackId = $this->getRequest()->getParam('track_id');
        /** @var \Magento\Sales\Model\Order\Shipment\Track $track */
        $track = $this->_objectManager->create('Magento\Sales\Model\Order\Shipment\Track')->load($trackId);
        if ($track->getId()) {
            try {
                $this->_title->add(__('Shipments'));
                $this->shipmentLoader->setOrderId($this->getRequest()->getParam('order_id'));
                $this->shipmentLoader->setShipmentId($this->getRequest()->getParam('shipment_id'));
                $this->shipmentLoader->setShipment($this->getRequest()->getParam('shipment'));
                $this->shipmentLoader->setTracking($this->getRequest()->getParam('tracking'));
                $shipment = $this->shipmentLoader->load();
                if ($shipment) {
                    $track->delete();

                    $this->_view->loadLayout();
                    $response = $this->_view->getLayout()->getBlock('shipment_tracking')->toHtml();
                } else {
                    $response = [
                        'error' => true,
                        'message' => __('Cannot initialize shipment for delete tracking number.')
                    ];
                }
            } catch (\Exception $e) {
                $response = ['error' => true, 'message' => __('Cannot delete tracking number.')];
            }
        } else {
            $response = ['error' => true, 'message' => __('Cannot load track with retrieving identifier.')];
        }
        if (is_array($response)) {
            $response = $this->_objectManager->get('Magento\Core\Helper\Data')->jsonEncode($response);
            $this->getResponse()->representJson($response);
        } else {
            $this->getResponse()->setBody($response);
        }
    }
}
