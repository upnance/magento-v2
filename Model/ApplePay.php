<?php

namespace Upnance\Gateway\Model;

/**
 * Pay In Store payment method model
 */
class ApplePay extends \Magento\Payment\Model\Method\AbstractMethod
{
    /**
     * Payment code
     *
     * @var string
     */
    protected $_code = 'upnance_applepay';

    /**
     * @var string
     */
    protected $_title = 'Apple Pay';

    /**
     * Availability option
     *
     * @var bool
     */

    /**
     * @var bool
     */
    protected $_isInitializeNeeded = true;

    /**
     * @var bool
     */
    protected $_canCapture              = true;

    /**
     * @var bool
     */
    protected $_canRefund               = true;

    /**
     * @var bool
     */
    protected $_isGateway               = true;

    /**
     * @var bool
     */
    protected $_canUseForMultishipping  = false;

    /**
     * Payment Method feature
     *
     * @var bool
     */
    protected $_canCapturePartial = true;

    /**
     * Payment Method feature
     *
     * @var bool
     */
    protected $_canRefundInvoicePartial = true;

    /**
     * Can be used in regular checkout
     *
     * @return bool
     * @deprecated 100.2.0
     */
    public function canUseCheckout()
    {
        return $this->isSafariBrowser();
    }

    /**
     * @param $lan
     * @return mixed
     */
    public function calcLanguage($lan)
    {
        $map_codes = array (
            'nb' => 'no',
            'nn' => 'no'
        );

        $splitted = explode('_', $lan);
        $lang = $splitted[0];
        if ( isset ( $map_codes[$lang] ) ) return $map_codes[$lang];
        return $lang;
    }

    /**
     * @param \Magento\Payment\Model\InfoInterface $payment
     * @param float $amount
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function authorize(\Magento\Payment\Model\InfoInterface $payment, $amount)
    {
        if (!$this->canAuthorize()) {
            throw new \Magento\Framework\Exception\LocalizedException(__('The authorize action is not available.'));
        }
        return $this;
    }

    /**
     * @param \Magento\Payment\Model\InfoInterface $payment
     * @param float $amount
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function capture(\Magento\Payment\Model\InfoInterface $payment, $amount)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $adapter = $objectManager->get(\Upnance\Gateway\Model\Adapter\UpnanceAdapter::class);
        $parts = explode('-',$payment->getTransactionId());
        $order = $payment->getOrder();
        $transaction = $parts[0];

        if (!$this->canCapture()) {
            throw new \Magento\Framework\Exception\LocalizedException(__('The capture action is not available.'));
        }

        try {
            $adapter->capture($order, $transaction, $amount);
        } catch (LocalizedException $e) {
            throw new \Magento\Framework\Exception\LocalizedException(__($e->getMessage()));
        }

        return $this;
    }

    /**
     * @param \Magento\Payment\Model\InfoInterface $payment
     * @param float $amount
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function refund(\Magento\Payment\Model\InfoInterface $payment, $amount)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $adapter = $objectManager->get(\Upnance\Gateway\Model\Adapter\UpnanceAdapter::class);
        $parts = explode('-',$payment->getTransactionId());
        $order = $payment->getOrder();
        $transaction = $parts[0];

        if (!$this->canRefund()) {
            throw new \Magento\Framework\Exception\LocalizedException(__('The refund action is not available.'));
        }

        try {
            $adapter->refund($order, $transaction, $amount);
        } catch (LocalizedException $e) {
            throw new \Magento\Framework\Exception\LocalizedException(__($e->getMessage()));
        }

        return $this;
    }

    /**
     * @param \Magento\Payment\Model\InfoInterface $payment
     * @return $this
     */
    public function cancel(\Magento\Payment\Model\InfoInterface $payment)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $adapter = $objectManager->get(\Upnance\Gateway\Model\Adapter\UpnanceAdapter::class);
        $parts = explode('-',$payment->getTransactionId());
        $order = $payment->getOrder();
        $transaction = $parts[0];

        if($transaction) {
            try {
                $adapter->cancel($order, $transaction);
            } catch (LocalizedException $e) {
                throw new \Magento\Framework\Exception\LocalizedException(__($e->getMessage()));
            }
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function isSafariBrowser(){
        $u_agent = $_SERVER['HTTP_USER_AGENT'];
        $issafari = false;
        if (preg_match('/Safari/i',$u_agent)){
            $issafari = (!preg_match('/Chrome/i',$u_agent));
        }

        return $issafari;
    }
}
