@extends('../layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-xs-12">
    		<div class="invoice-title">
    			<h2>Invoice</h2><h3 class="pull-right">Invoice # {{ $invoice->id }}</h3>
    		</div>
    		<hr>
    		<div class="row">
    			<div class="col-xs-6">
    				<address>
    				<strong>Billed To:</strong><br>
    					{{$tenant->name}}<br>
    				</address>
    			</div>
    			<div class="col-xs-6 text-right">
    				<address>
        			<strong>From:</strong><br>
    					{{$landlord->name}}<br>
    				</address>
    			</div>
    		</div>
    		<div class="row">
    			<div class="col-xs-6">
    				<address>
    					<strong>Payment Method:</strong><br>
    					Visa ending **** 4242<br>
    					<strong>Email:</strong><br>
    					{{$tenant->email}}
    				</address>
    			</div>
    			<div class="col-xs-6 text-right">
    				<address>
    					<strong>Invoice Due:</strong><br>
    					{{$invoice->due_date}}<br><br>
    				</address>
    			</div>
    		</div>
    	</div>
    </div>
    
    <div class="row">
    	<div class="col-md-12">
    		<div class="panel panel-default">
    			<div class="panel-heading">
    				<h3 class="panel-title"><strong>Invoice summary</strong></h3>
    			</div>
    			<div class="panel-body">
    				<div class="table-responsive">
    					<table class="table table-condensed">
    						<thead>
                                <tr>
        							<td><strong>Item</strong></td>
        							<td class="text-center"></td>
    								<td class="text-center"></td>
        							<td class="text-right"><strong>Value</strong></td>
                                </tr>
    						</thead>
    						<tbody>
    							@foreach ($items as $item)
    							<tr>
    								<td>
    								    {{$item->description}}
    							    </td>
    								<td class="text-center"></td>
    								<td class="text-center"></td>
                                    <td class= "text-right">
                                        ${{$item->value}}
                                    </td>
    								<td class="text-center">
    					                <form class="form-inline" action="{{ url('invoice/'.$contract->id.'/'.$invoice->id.'/'.$item->id) }}" method="POST">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                            
                                            <button type="submit" class="btn btn-danger">
                                                <i class="fa fa-btn fa-trash"></i>
                                            </button>
                                            
                                        </form>
    								</td>
    								
    							</tr>
                                @endforeach
    							<tr>
    								<td class="no-line"></td>
    								<td class="no-line"></td>
    								<td class="no-line text-center"><strong>Total</strong></td>
    								<td class="no-line text-right">${{$total}}</td>
    							</tr>
    						</tbody>
    					</table>
    				</div>
    			</div>
    		</div>
    	
    	<div class="row">
            <button type="button" class="btn btn-default" data-toggle="collapse" data-target="#add" id="itemAddBtn"><i class="fa fa-plus"></i> Add Item</button>
        </div>
        <div class="row panel-body collapse" id="add">
        <!-- Display Validation Errors -->
        @include('common.errors')

        <!-- New Task Form -->
        <form action="{{ url('invoice/'.$contract->id.'/'.$invoice->id) }}" method="POST" class="form-horizontal">
            {{ csrf_field() }}

            <!-- Task Name -->
            <div class="form-group">
                <div class="form-row">
                    
                    <div class="col-sm-6">
                        <label for="item-name" class="col-sm-12">Item:</label>
                        <input type="text" name="desc" id="item-name" class="form-control">
                    </div>
                    
                    
                    <div class="col-sm-6">
                        <label for="item-value" class="col-sm-12">Value:</label>
                        <input type="number" name="value" value="1000" step="0.01" data-number-to-fixed="2" data-number-stepfactor="100" class="currency form-control" id="item-value">
                    </div>
                </div>
            </div>

            <!-- Add Task Button -->
            <div class="form-group">
                <div align="center">
                    <button type="submit" class="btn btn-default" id="itemSubBtn">
                        Submit
                    </button>
                </div>
            </div>
        </form>
    </div>
    	</div>
    </div>
</div>
@endsection