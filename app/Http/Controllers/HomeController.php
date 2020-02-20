<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Carbon\Carbon;
use Laravel\Cashier\PaymentMethod;
use App\User;

use Auth;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Process subscription
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        $user   = User::find( Auth::user()->id );
        try {

            $paymentMethod = $request->paymentMethod;

            $user->newSubscription('monthly', 'plan_GZQvP96jpU4OF7')->skipTrial()->create($paymentMethod);

            return redirect('home')->with('success','Subscription is completed.');
          } catch (\Exception $e)
          {
            return redirect()->back()->with('error', 'There were some issue with the payment. Please try again later.');
          }
      }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index()
    {
      $user   = User::find( Auth::user()->id );
      $trial  = false;

      // check for user payment method and set default payment method
      if ( !$user->hasPaymentMethod()) {
          $user->updateDefaultPaymentMethodFromStripe();
      }


      // check for user trial
      if( $user->subscription('monthly')->stripe_status != 'active' && !$user->subscription('monthly')->onTrial() )
      {
          // show form payment to user
          return view('payment', [
              'intent'      => $user->createSetupIntent(),
              'user'        => $user,
          ]);
      }
      // Check for user if subscribed
      if ( $user->subscribed('monthly') )
      {
          return view('home', compact(['user']));
      }
      else {

        // show form payment to user
        return view('payment', [
            'intent'      => $user->createSetupIntent(),
        ]);
      }
    }
}
