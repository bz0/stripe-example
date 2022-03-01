<?php

namespace App\Http\Controllers\Payment\Stripe;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class StripeController extends Controller
{
    const UNSUBSCRIBED = 'unsubscribed';
    const CANCELLED = 'cancelled';
    const SUBSCRIBED = 'subscribed';

    /**
     * 課金ステータスを取得
     */
    public function status()
    {
        $status = self::UNSUBSCRIBED;
        $user = auth()->user();
        $details = [];

        if($user->subscribed('main')) { // 課金履歴あり
            if($user->subscription('main')->cancelled()) {  // キャンセル済み
                $status = self::CANCELLED;
            } else {    // 課金中
                $status = self::SUBSCRIBED;
            }

            $subscription = $user->subscriptions->first(function($value){
                return ($value->name === 'main');
            })->only('ends_at', 'stripe_plan');

            $details = [
                'end_date' => ($subscription['ends_at']) ? $subscription['ends_at']->format('Y-m-d') : null,
                'plan' => \Arr::get(config('services.stripe.plans'), $subscription['stripe_plan']),
                'card_last_four' => $user->card_last_four
            ];
        }

        return [
            'status' => $status,
            'details' => $details
        ];
    }

    /**
     * 課金する
     */
    public function subscribe(Request $request)
    {
        $user = $request->user();

        if(!$user->subscribed('main')) {
            $payment_method = $request->payment_method;
            $plan = $request->plan;
            $user->newSubscription('main', $plan)->create($payment_method);
            $user->load('subscriptions');
        }

        return $this->status();
    }

    /**
     * 課金のキャンセル
     */
    public function cancel(Request $request)
    {
        $request->user()
            ->subscription('main')
            ->cancel();
        return $this->status();
    }

    /**
     * キャンセルをもとに戻す
     */
    public function resume(Request $request)
    {
        $request->user()
            ->subscription('main')
            ->resume();
        return $this->status();
    }

    /**
     * プランの変更
     */
    public function changePlan(Request $request)
    {
        $plan = $request->plan;
        $request->user()
            ->subscription('main')
            ->swap($plan);
        return $this->status();
    }

    /**
     * カードを変更する
     */
    public function updateCard(Request $request)
    {
        $payment_method = $request->payment_method;
        $request->user()
            ->updateDefaultPaymentMethod($payment_method);
        return $this->status();
    }
}
