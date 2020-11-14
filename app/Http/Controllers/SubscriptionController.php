<?php

namespace App\Http\Controllers;

use App\Http\Resources\SubscriptionResource;
use App\Models\Config;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function show(Subscription $subscription)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function edit(Subscription $subscription)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subscription $subscription)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subscription $subscription)
    {
        //
    }

    public function getAvailability()
    {
        return response()->json(getSubsAvailability(), 200);
    }

    public function getSize()
    {
        return response()->json(getSubsSize(), 200);
    }

    public function getSubsState()
    {
        $user = Auth::user();
        $has_subcription = false;
        $subscriptions = $user->subscription()->where('is_expired', false)->get();

        if (!$subscriptions->isEmpty()) {
            $has_subcription = true;
        }

        return response()->json([
            'hasSubscription' => $has_subcription,
            'data' => SubscriptionResource::collection($subscriptions)
        ], 200);
    }

    public function purchaseSubs(Request $request)
    {
        $user = Auth::user();
        $subs_price = Config::subscriptionPrice()->value;

        $subscription = new Subscription();

        $subscription->user_id = $user->id;
        $subscription->valid_at = $request->valid_at;
        $subscription->valid_till = $request->valid_till;
        $subscription->is_active = $request->mode == 'purchase' ? true : false;
        $subscription->save();

        $user->apcard_balance = $user->apcard_balance - $subs_price;
        $user->update();

        return response()->json([
            'message' => 'Purchase successful',
        ], 200);
    }

    public function terminateSubs(Request $request)
    {
        $user = Auth::user();

        $user->subscription()->where('is_expired', false)->update(['is_active' => false, 'is_expired' => true]);

        return response()->json([
            'message' => 'Terminate successful',
        ], 200);
    }
}
