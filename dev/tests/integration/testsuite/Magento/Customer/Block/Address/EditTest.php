<?php
/**
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
namespace Magento\Customer\Block\Address;

/**
 * Tests Address Edit Block
 */
class EditTest extends \PHPUnit_Framework_TestCase
{
    /** @var Edit */
    protected $_block;

    /** @var  \Magento\Customer\Model\Session */
    protected $_customerSession;

    /** @var \Magento\Backend\Block\Template\Context */
    protected $_context;

    /** @var string */
    protected $_requestId;

    protected function setUp()
    {
        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

        $this->_customerSession = $objectManager->get('\Magento\Customer\Model\Session');
        $this->_customerSession->setCustomerId(1);

        $this->_context = $objectManager->get('Magento\Backend\Block\Template\Context');
        $this->_requestId = $this->_context->getRequest()->getParam('id');
        $this->_context->getRequest()->setParam('id', '1');

        $objectManager->get('Magento\Framework\App\State')->setAreaCode('frontend');

        /** @var $layout \Magento\Framework\View\Layout */
        $layout = $objectManager->get('Magento\Framework\View\LayoutInterface');
        $currentCustomer = $objectManager->create(
            'Magento\Customer\Helper\Session\CurrentCustomer',
            array('customerSession' => $this->_customerSession)
        );
        $this->_block = $layout->createBlock(
            'Magento\Customer\Block\Address\Edit',
            '',
            array('customerSession' => $this->_customerSession, 'currentCustomer' => $currentCustomer)
        );
    }

    protected function tearDown()
    {
        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
        $this->_customerSession->unsCustomerId();
        $this->_context->getRequest()->setParam('id', $this->_requestId);
        /** @var \Magento\Customer\Model\AddressRegistry $addressRegistry */
        $addressRegistry = $objectManager->get('Magento\Customer\Model\AddressRegistry');
        //Cleanup address from registry
        $addressRegistry->remove(1);
        $addressRegistry->remove(2);

        /** @var \Magento\Customer\Model\CustomerRegistry $customerRegistry */
        $customerRegistry = $objectManager->get('Magento\Customer\Model\CustomerRegistry');
        //Cleanup customer from registry
        $customerRegistry->remove(1);
    }

    /**
     * @magentoDataFixture Magento/Customer/_files/customer.php
     */
    public function testGetSaveUrl()
    {
        $this->assertEquals('http://localhost/index.php/customer/address/formPost/', $this->_block->getSaveUrl());
    }

    /**
     * @magentoDataFixture Magento/Customer/_files/customer.php
     * @magentoDataFixture Magento/Customer/_files/customer_address.php
     */
    public function testGetRegionId()
    {
        $this->assertEquals(1, $this->_block->getRegionId());
    }

    /**
     * @magentoDataFixture Magento/Customer/_files/customer.php
     * @magentoDataFixture Magento/Customer/_files/customer_address.php
     */
    public function testGetCountryId()
    {
        $this->assertEquals('US', $this->_block->getCountryId());
    }

    /**
     * @magentoDataFixture Magento/Customer/_files/customer.php
     * @magentoDataFixture Magento/Customer/_files/customer_two_addresses.php
     */
    public function testGetCustomerAddressCount()
    {
        $this->assertEquals(2, $this->_block->getCustomerAddressCount());
    }

    /**
     * @magentoDataFixture Magento/Customer/_files/customer.php
     */
    public function testCanSetAsDefaultShipping()
    {
        $this->assertEquals(0, $this->_block->canSetAsDefaultShipping());
    }

    /**
     * @magentoDataFixture Magento/Customer/_files/customer.php
     */
    public function testIsDefaultBilling()
    {
        $this->assertFalse($this->_block->isDefaultBilling());
    }

    /**
     * @magentoDataFixture Magento/Customer/_files/customer.php
     * @magentoDataFixture Magento/Customer/_files/customer_address.php
     */
    public function testGetStreetLine()
    {
        $this->assertEquals('Green str, 67', $this->_block->getStreetLine(1));
        $this->assertEquals('', $this->_block->getStreetLine(2));
    }
}
