<?php

namespace App\Http\Controllers;

use Mail;
use Illuminate\Http\Request;
use App\Contract;
use App\Http\Requests;
use App\User;
use Auth;
//
//

use App\Http\Controllers\Controller;
use App\Repositories\ContractRepository;
//
//

class ContractController extends Controller
{
    //
    protected $contracts;
    /**
     * Create a new controller instance.
     */
    public function __construct(ContractRepository $contracts)
    {
        $this->middleware('auth');
        //TODO: auth for contracts
        $this->contracts = $contracts;
        
    }
    /**
     * Display a list of all the user's contracts.
     * 
     * @param Request $request
     * @return Response
     */
     public function index(Request $request)
     {
         $contr = $request->user()->contracts()->get();
         return view('contracts.index', [
             'contracts' => $contr
             ]);
             
     }
     public function store(Request $request)
     {
         
         /*Mail::raw("Hello from AsURent, we've got mail", function($message){
            $message -> to('email@email.email'); 
         });*/
         
        //return redirect('/contracts');
        $this->validate($request, [
             'name' => 'required|max:255',
             'description' => 'required|max:22',
        ]);
        
        /*$request->contracts()->create([
            'name' => $request->name,
            'description' => $request->description,
            ]);*/
        $cont = new Contract;
        $cont -> name = $request -> name;
        $cont -> description = $request -> description;
        $cont -> base_rate = $request -> base_rate;
        $cont -> landlord_id = $request -> user() -> id;//in version1 landlord_id is innocuous
        //$cont -> tenant_id = $cont->setTenant($request -> tenant);
        $cont -> tenant_id = Auth::user()->id; //in version1 all users are tenants.
        $cont -> save();
        //Make a new plan in Stripe...
        \Stripe\Stripe::setApiKey(env('ASURENT_STRIPE_SECRET'));
        
        $plan = \Stripe\Plan::create(array(
          "amount" => $cont->base_rate*100,
          "interval" => "month",
          "name" => $cont->name,
          "currency" => "usd",
          "id" => $cont->id,
          "statement_descriptor" => $request->description
        ));
        
        //get tenant and add to plan
        $tenant = User::where('email', $request->email)->first();
        
        //dd($tenant->stripe_customer_id);
        if($tenant == null){
            $tenant = User::findOrFail($cont->tenant_id);
        }
        if($tenant == null)
        {
            return "couldn't find tenant";
        }
        //dd($tenant);
        /*if($tenant->stripe_customer_id == null)
        {
            $customer = \Stripe\Customer::create(array(
                "description" => "Contract Created Customer for AsURent",
                "source" => null // will be replaced with btok...
            ));
            $tenant->stripe_customer_id = $customer->id;
        }*/
        //if ($customer->source == null)... get btok..
        
        $subscription = \Stripe\Subscription::create(array(
            "customer" => $tenant->stripe_customer_id, //customer needs a source object before this step
            "plan" => $plan->id
        ));
        $cont -> stripe_subscription_id = $subscription->id;
        $cont -> save();
        //dd($subscription);
        //
        //$customer = \Stripe\Customer::create(array(
        //  "description" => "Customer for AsURent",
        //  "source" => null // will be replaced with btok...
        //));
        //dd($customer->id);
        
        
        //dd($customer);
        return redirect('/contracts');
     }
     public function editForm(Request $request, Contract $contract)
     {
         return view('contracts.edit', [
            'contract' => $contract 
            ]);
     }
     public function edit(Request $request, Contract $contract)
     {
         
         $this->authorize('edit', $contract);//only landlords can authorize edit of contract in version2.
         $contract -> name = $request -> name;
         $contract -> description = $request -> description;
         //$contract -> base_rate = $request -> base_rate;
         
         $contract -> save();
         //edit the stripe end too:
         \Stripe\Stripe::setApiKey(env('ASURENT_STRIPE_SECRET'));
         $stripe_plan = \Stripe\Plan::retrieve($contract->id);
         $stripe_plan->name = $request -> name;
         //ammount is by design not editable.
         if(strlen($request->statement_descriptor) <= 22)
         {
             $stripe_plan->statement_descriptor = $request->description;
             //only edit statement descriptor if 22 or less characters.
             //the client side should have checked to make sure it was 22 or less
             //but in case it didn't, we check again here and if it is then throw it out.
         }
         $stripe_plan->save();
         return redirect('/contracts');
     }
     /**
      * Destroy the given contract.
      * 
      * @param Request $request
      * @param contract $contract
      * @return Response
      */ 
     public function destroy(Request $request, Contract $contract)
     {
        /**$this->authorize('destroy', $contract); 
        
        //Delete the contract..
        $contract->delete();
        return redirect('/contracts');
        */
        
        //TODO: authorization for contract manipulation
        $this->authorize('destroy', $contract);
        //unsubscribe user from the Stripe plan:
        \Stripe\Stripe::setApiKey(env('ASURENT_STRIPE_SECRET'));
        $stripe_subscription = \Stripe\Subscription::retrieve($contract->stripe_subscription_id);
        $stripe_subscription->cancel();
        //delete Stripe plan:
        $stripe_plan = \Stripe\Plan::retrieve($contract->id);
        $stripe_plan->delete();
        //delete it from our database
        $contract->delete();
        
        
        
        return redirect('/contracts');
     }
}
