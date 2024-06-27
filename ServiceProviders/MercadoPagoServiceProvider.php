<?php

namespace Flute\Modules\MercadoPago\ServiceProviders;

use Flute\Core\Payments\Events\BeforeGatewayProcessingEvent;
use Flute\Core\Payments\Events\RegisterPaymentFactoriesEvent;
use Flute\Core\Support\ModuleServiceProvider;
use Flute\Modules\MercadoPago\Listeners\PaymentListener;

class MercadoPagoServiceProvider extends ModuleServiceProvider
{
    public array $extensions = [];

    public function boot(\DI\Container $container): void
    {
        events()->addDeferredListener(RegisterPaymentFactoriesEvent::NAME, [PaymentListener::class, 'registerMercadoPago']);
        events()->addDeferredListener(BeforeGatewayProcessingEvent::NAME, [PaymentListener::class, 'handle']);
    }

    public function register(\DI\Container $container): void
    {
    }
}