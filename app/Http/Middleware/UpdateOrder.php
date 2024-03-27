<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Http\Traits\GeneralTrait;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
class UpdateOrder
{
    use GeneralTrait;

    public function handle(Request $request, Closure $next)
    {
        $order=Order::find($request->route('id'));
        if($order->user_id==Auth::id())
        return $next($request);
        else
        return $this->apiResponse(null,false,'you do not have this order',403 );
      
    }
}
