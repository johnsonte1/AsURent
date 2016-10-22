<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contract;
use App\Http\Requests;
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
        //return redirect('/contracts');
        $this->validate($request, [
             'name' => 'required|max:255',
        ]);
        
        /*$request->contracts()->create([
            'name' => $request->name,
            'description' => $request->description,
            ]);*/
        $cont = new Contract;
        $cont -> name = $request -> name;
        $cont -> description = $request -> description;
        $cont -> base_rate = $request -> base_rate;
        $cont -> landlord_id = $request -> user() -> id;
        $cont -> tenant_id = $cont->setTenant($request -> tenant);

        $cont -> save();
        //need to create customer id here...
        
        \Stripe\Stripe::setApiKey("sk_test_KC4D0LWdLrKOyv4S5tDXIXWX");
        
        //need to get the stripe tok... get it from stripe.js, let stripe js form point to here.
        
        $customer = \Stripe\Customer::create(array(
          "description" => "Customer for liam.anderson@example.com",
          "source" => "tok_18zH8sIRPfaQXufG0FjNiqVU" // obtained with Stripe.js
        ));
        \Stripe\Subscription::create(array(
          "customer" => $customer,
          "plan" => "base"
        ));
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
         $contract -> name = $request -> name;
         $contract -> description = $request -> description;
         $contract -> base_rate = $request -> base_rate;
         
         $contract -> save();
         
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
        $contract->delete();
        
        return redirect('/contracts');
     }
}
