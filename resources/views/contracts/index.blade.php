@extends('../layouts.app')

@section('content')

    <!-- Bootstrap Boilerplate... -->
<section>
<div class="container">
    

    <!-- TODO: Current Tasks -->
    @if (count($contracts) >= 0)
        <div class="row panel panel-default">
            <div class="panel-heading">
                Current Contracts
            </div>

            <div class="panel-body">
                <table class="table table-striped task-table">

                    <!-- Table Headings -->
                    <thead>
                        <th>Contract</th>
                        <th>Description</th>
                        <th>Rent</th>
                        <th>&nbsp;</th>
                    </thead>

                    <!-- Table Body -->
                    <tbody>
                        @foreach($contracts as $contract)
                            <tr>
                                <!-- Task Name -->
                                <td class="table-text">
                                    <div>{{ $contract->name }}</div>
                                </td>
                                <td class="table-text">
                                    <div>{{ $contract->description }}</div>
                                </td>
                                <td class="table-text">
                                    <div>{{ $contract->base_rate }}</div>
                                </td>

                                <td>
                                    <!-- Delete Button -->
                                    <form action="{{ url('contract/'.$contract->id) }}" method="POST">
                                        {{csrf_field()}}
                                        {{method_field('DELETE')}}
                                        <a href="{{ url('invoices/'.$contract->id) }}" class="btn btn-success bBtn" role="button">
                                            Invoices
                                        </a>
                                        <a href="{{ url('contract/'.$contract->id) }}" class="btn btn-warning oBtn" role="button"><i class="fa fa-pencil-square-o"></i>Edit</a>
                                        <button type="submit" id="delete-task-{{ $contract->id }}" class="btn btn-danger">
                                            <i class="fa fa-btn fa-trash"></i>Delete 
                                        </button>
                                        
                                    </form>
                                </td>
                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="row">
            <button type="button" class="btn btn-default" data-toggle="collapse" data-target="#add" id="conAddBtn"><i class="fa fa-plus"></i> Add Contract</button>
        </div>
        <div class="row panel-body collapse" id="add">
        <!-- Display Validation Errors -->
        @include('common.errors')

        <!-- New Task Form -->
        <form action="{{ url('contracts') }}" method="POST" class="form-horizontal">
            {{ csrf_field() }}

            <!-- Task Name -->
            <div class="form-group">
            <div class="form-row">
                
                
                <div class="col-sm-6">
                    <label for="contract-name" class="col-sm-12">Name:</label>
                    <input type="text" name="name" id="contract-name" class="form-control">
                </div>
                
                
                <div class="col-sm-6">
                    <label for="contract-description" class="col-sm-12">Description:</label>
                    <input type="text" name="description" id="contract-description" class="form-control">
                </div>
            </div>
            <div class="form-row">
                <!--<div class="col-sm-6">
                    <label for="contract-tenant" class="col-sm-12">Tenant Email:</label>
                    <input type="email" name="tenant" class="form-control" id="contract-tenant">
                </div>-->
                <div class="col-sm-6">
                    <label for="contract-rent" class="col-sm-12">Monthly Rent:</label>
                    <input type="number" name="base_rate" value="1000" min="0" step="0.01" data-number-to-fixed="2" data-number-stepfactor="100" class="currency form-control" id="contract-base_rate">
                </div>
            </div>
            </div>

            <!-- Add Task Button -->
            <div class="form-group">
                <div align="center">
                    <button type="submit" class="btn btn-default" id="conSubBtn">
                        Submit
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
</section>
    @endif
@endsection