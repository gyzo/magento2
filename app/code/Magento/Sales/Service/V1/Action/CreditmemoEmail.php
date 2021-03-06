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
namespace Magento\Sales\Service\V1\Action;

use Magento\Sales\Model\Order\CreditmemoRepository;

/**
 * Class CreditmemoEmail
 */
class CreditmemoEmail
{
    /**
     * @var CreditmemoRepository
     */
    protected $creditmemoRepository;

    /**
     * @var \Magento\Sales\Model\Order\CreditmemoNotifier
     */
    protected $creditmemoNotifier;

    /**
     * @param CreditmemoRepository $creditmemoRepository
     * @param \Magento\Sales\Model\Order\CreditmemoNotifier $notifier
     */
    public function __construct(
        CreditmemoRepository $creditmemoRepository,
        \Magento\Sales\Model\Order\CreditmemoNotifier $notifier
    ) {
        $this->creditmemoRepository = $creditmemoRepository;
        $this->creditmemoNotifier = $notifier;
    }

    /**
     * Invoke notifyUser service
     *
     * @param int $id
     * @return bool
     */
    public function invoke($id)
    {
        /** @var \Magento\Sales\Model\Order\Creditmemo $creditmemo */
        $creditmemo = $this->creditmemoRepository->get($id);
        return $this->creditmemoNotifier->notify($creditmemo);
    }
}
