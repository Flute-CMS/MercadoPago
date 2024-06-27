<?php

namespace Flute\Modules\MercadoPago\Listeners;

use Flute\Core\Payments\Events\BeforeGatewayProcessingEvent;
use Omnipay\MercadoPago\Item;

class PaymentListener
{
    public static function handle(BeforeGatewayProcessingEvent $event)
    {
        $gateway = $event->getGateway();

        if ($gateway->getShortName() !== 'MercadoPago')
            return;

        $paymentData = $event->getPaymentdata();

        $item = new Item();
        $item->setName("Payment");
        $item->setCategoryId("tickets");
        $item->setQuantity(1);
        $item->setCurrencyId(isset($paymentData['currency']) ? $paymentData['currency'] : config('lk.currency_view'));
        $item->setPrice($event->getInvoice()->originalAmount);
        $items = array("items" => [$item]);

        $responseToken = $gateway->requestToken($items)->send();
        $dataToken = $responseToken->getData();
        $gateway->setAccessToken($dataToken->access_token);

        $paymentData['items'] = $items['items'];

        $event->setPaymentData($paymentData);
    }

    public static function registerMercadoPago()
    {
        app()->getLoader()->addPsr4('Omnipay\\MercadoPago\\', module_path('MercadoPago', 'Omnipay/'));
        app()->getLoader()->register();
    }
}