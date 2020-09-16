<?php

namespace App\Http\Controllers\Payment;

use App\Events\SendDonationNotification;
use App\Http\Controllers\Payment\BillingPayment;
use App\Http\Controllers\Payment\PaymentType;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PaymentsController extends Controller
{

    /**
     * @var Session
     */
    private $session;

    /**
     * @var BillingPayment
     */
    private $billingPayment;

    /**
     * PaymentsController constructor.
     * @param BillingPayment $billingPayment
     * @param Session $session
     */
    public function __construct(BillingPayment $billingPayment, Session $session)
    {

        $this->session = $session;
        $this->billingPayment = $billingPayment;
    }

    /**
     * @param string $nickname
     * @return \Inertia\Response
     */
    public function show(string $nickname)
    {
        return \Inertia\Inertia::render('Payment',
            [
                'user' => [
                    'id' => 1,
                    'nickname' => $nickname,
                    'profile_image' => $nickname == 'YanGusik' ? '/mem.png' : 'https://cdn.discordapp.com/avatars/338416806408486913/70b6b7fbc821165eeb30d37a568af66c.png?size=128'
                ]
            ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function pay(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'amount' => 'required|integer|min:1',
            'currency' => ['required', Rule::in(['rub', 'usd', 'euro'])],
            'type_payment' => ['required', Rule::in(PaymentType::TYPES)],
            'nickname' => 'required|string',
            'message' => 'string:null',
            'user_id' => 'exists:users,id',
        ]);

        $user = User::FindOrFail($request['user_id']);

        $payment = $user->payments()->create([
            'amount' => $request->amount,
            'email' => $request->email,
            'currency' => $request->currency,
            'type_payment' => $request->type_payment,
            'nickname' => $request->nickname,
            'message' => $request->message,
            'status' => Payment::NEW
        ]);

        $gateway = $this->billingPayment->create($payment->type_payment);
        $response = $gateway->purchase($payment->toArray())->send();

        if ($response->isRedirect()) {
            // redirect to offsite payment gateway
            return response()->json($response->getRedirect());
        } elseif ($response->isSuccessful()) {
            $payment = Payment::Find($payment->id);
            $payment->status = Payment::PAID;
            $payment->save();
            event(new SendDonationNotification($payment->nickname, $payment->amount));
            return response()->json($response->getMessage());
        } else {
            // payment failed: display message to customer
            return response()->json($response->getError());
        }
    }

    /**
     * @param Request $request
     * @return \Inertia\Response
     */
    public function completed(Request $request)
    {
        $data = [];

        if (!$request->exists('paymentId')) {
            $data = [
                'status' => 'failed',
                'message' => 'Transaction is declined'
            ];
        } else {
            $transaction = $this->billingPayment->completePurchase($request->paymentId);
            $response = $transaction->send();

            if ($response->isSuccessful()) {
                $payment = Payment::Find($response->getPayment()['id']);
                $payment->status = Payment::PAID;
                $payment->save();
                event(new SendDonationNotification($payment->nickname, $payment->amount));
                $data = [
                    'status' => 'success',
                    'message' => 'yupi'
                ];

            } else {
                $payment = Payment::Find($response->getPayment()['id']);
                $payment->status = Payment::CANCELLED;
                $payment->save();
                $data = [
                    'status' => 'failed',
                    'message' => 'Transaction is cancelled'
                ];
            }
        }

        return \Inertia\Inertia::render('Payment',
            [
                'redirect_data' => $data
            ]);
    }

    /**
     * @param Request $request
     */
    public function cancelled(Request $request)
    {

    }
}
