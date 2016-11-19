@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Conveniently send a single payment to AsURent here.
                </div>
                
                <div class="panel-body">
                    
                <script>
                    //stripe response handler
                      Stripe.setPublishableKey('pk_test_89jOqsSrEjxCjEotST32cFc9');
                      var stripeResponseHandler = function(status, response) {
                          var $form = $('#payment-form');
                        
                          if (response.error) {
                            // Show the errors on the form
                            $form.find('.payment-errors').text(response.error.message);
                            $form.find('button').prop('disabled', false);
                          } else {
                            // token contains id, last4, and card type
                            var token = response.id;
                            // Insert the token into the form so it gets submitted to the server
                            $form.append($('<input type="hidden" name="stripeToken" />').val(token));
                            // and submit
                            $form.get(0).submit();
                      }
                    };

                    //stripe handle submit button
                    $(function() {
                      var $form = $('#payment-form');
                      $form.submit(function(event) {
                        // Disable the submit button to prevent repeated clicks:
                        $form.find('.submit').prop('disabled', true);
                    
                        // Request a token from Stripe:
                        Stripe.card.createToken($form, stripeResponseHandler);
                        console.log("HEllloOOO?");
                        // Prevent the form from being submitted:
                        return false;
                      });
                    });
                    
                </script>
             
                <form action="/payOnce" method="POST" id="payment-form">
                      {{csrf_field()}}
                      <span class="payment-errors"></span>
                      
                      <div class="form-row">
                        <label>
                          <span>Email</span>
                          <input type="text" name="email">
                        </label>
                      </div>
                      <div class="form-row">
                        <label>
                          <span>Card Number</span>
                          <input type="text" size="20" data-stripe="number" value="4242424242424242">
                        </label>
                      </div>
                    
                      <div class="form-row">
                        <label>
                          <span>Expiration (MM/YY)</span>
                          <input type="text" size="2" data-stripe="exp_month" value="1">
                        </label>
                        <span> / </span>
                        <input type="text" size="2" data-stripe="exp_year" value="19">
                      </div>
                    
                      <div class="form-row">
                        <label>
                          <span>CVC</span>
                          <input type="text" size="4" data-stripe="cvc", value="111">
                        </label>
                      </div>
                      <input type="submit" class="submit" value="Submit Payment">
                </form>
            </div>
        </div>
    </div>
    </div>
    <!-- Plaid Stripe Link?-->
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
              <div class="panel panel-heading">
                Enable Recurring Billing
              </div>
              <div class="panel-body">
                    
                
    Connect Bank Account:
    <form id='plaid-link-form' method="POST" action="/plaidcurl"></form>
   <!-- <script
        src="https://cdn.plaid.com/link/stable/link-initialize.js"
        data-client-name="Chobits 4 lolz"
        data-form-id="plaid-link-form"
        data-key="test_key"
        data-product="auth"
        data-env="tartan">
    </script>-->
  <!--  <form method="POST" action="plaidcurl">-->
    <button id='linkButton'>Get Bank Token</button>
   <!-- </form>-->
<!--<button id='bofaButton'>Open Link - Bank of America</button>-->
  <script src="https://cdn.plaid.com/link/stable/link-initialize.js"></script>
  <script>
        var linkHandler = Plaid.create({
          selectAccount: true,
          env: 'tartan',
          clientName: 'AsURent Rent Collectionz',
          //key: '{{env('PLAID_PUBLIC')}}',
          //key: 'test_key',
          key: 'a246cdb456f73cc16c8c6b9c813e4a',
          //key: 'test_key',
          product: 'auth',
          onLoad: function() {
            // The Link module finished loading.
          },
          onSuccess: function(public_token, metadata) {
            // The onSuccess function is called when the user has successfully
            // authenticated and selected an account to use.
            //
            // When called, you will send the public_token and the selected
            // account ID, metadata.account_id, to your backend app server.
            //
            //$.post('/plaidcurl', {variable: public_token, variable: metadata});
            //sendDataToBackendServer({
            //   public_token: public_token,
            //   account_id: metadata.account_id
            // });
           // Set method to post by default if not specified.
      
          // The rest of this code assumes you are not using a library.
          // It can be made less wordy if you use one.
            function post(path, params, method) {
              method = method || "post"; // Set method to post by default if not specified.
          
              // The rest of this code assumes you are not using a library.
              // It can be made less wordy if you use one.
              var form = document.createElement("form");
              form.setAttribute("method", method);
              form.setAttribute("action", path);
          
              for(var key in params) {
                  if(params.hasOwnProperty(key)) {
                      var hiddenField = document.createElement("input");
                      hiddenField.setAttribute("type", "hidden");
                      hiddenField.setAttribute("name", key);
                      hiddenField.setAttribute("value", params[key]);
          
                      form.appendChild(hiddenField);
                   }
              }
              document.body.appendChild(form);
              form.submit();
            }
            
            post('/plaidcurl', {public_token1: public_token, account_id1: metadata.account_id});
            
            //console.log('Public Token: ' + public_token);
            //console.log('Customer-selected account ID: ' + metadata.account_id);
          },
          onExit: function(err, metadata) {
            // The user exited the Link flow.
            if (err != null) {
              // The user encountered a Plaid API error prior to exiting.
            }
            // metadata contains information about the institution
            // that the user selected and the most recent API request IDs.
            // Storing this information can be helpful for support.
          },
        });
        
        // Trigger the Bank of America login view directly
       // document.getElementById('bofaButton').onclick = function() {
       //   linkHandler.open('bofa');
        //};
        
        // Trigger the standard Institution Select view
        document.getElementById('linkButton').onclick = function() {
          linkHandler.open();
        };
        var sendDataToBackendServer = function(){
            
        }
      </script>
      </div>
    </div>
  </div>
</div>
@endsection
