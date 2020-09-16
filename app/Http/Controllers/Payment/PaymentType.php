<?php

namespace App\Http\Controllers\Payment;

class PaymentType
{
    const PAYPAL = 'paypal';
    const SBERBANK = 'sberbank';
    const YANDEX_MONEY = 'yandex_money';
    const BITCOIN = 'bitcoin';
    const QIWI = 'qiwi';
    const MTS = 'mts';

    const TYPES = [self::PAYPAL, self::SBERBANK, self::YANDEX_MONEY, self::BITCOIN, self::QIWI, self::MTS];
}
