<?php

namespace App\Http\Controllers\Payment;

class PaymentType
{
    const PAYPAL = 'paypal';
    const SBERBANK = 'sberbank';
    const MTS = 'mts';

    const TYPES = [self::PAYPAL, self::SBERBANK, self::MTS];
}
