@extends('../layouts.app')

@section('content')

    <!-- Bootstrap Boilerplate... -->
<div class="container">
    

    <!-- TODO: Current Tasks -->
    @if (count($invoices) >= 0)
        <div class="row panel panel-default">
            <div class="panel-heading">
                Current invoices
            </div>

            <div class="panel-body">
                <table class="table table-striped task-table">

                    <!-- Table Headings -->
                    <thead>
                        <th>Created</th>
                        <th>Due</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>&nbsp;</th>
                    </thead>

                    <!-- Table Body -->
                    <tbody>
                        @foreach ($invoices as $invoice)
                            <tr>
                                <!-- Task Name -->
                                <td class="table-text">
                                    <div>{{ $invoice->created_at->format('Y-m-d') }}</div>
                                </td>
                                <td class="table-text">
                                    <div>{{ $invoice->due_date }}</div>
                                </td>
                                <td class="table-text">
                                    <div>{{ $contract->base_rate }}</div>
                                </td>

                                <td>
                                    <!-- Delete Button -->
                                    <form action="{{ url('invoices/'.$contract->id.'/'.$invoice->id) }}" method="POST">
                                        {{csrf_field()}}
                                        {{method_field('DELETE')}}
                                        <a href="{{ url('invoice/'.$contract->id.'/'.$invoice->id) }}" class="btn btn-success bBtn" role="button">
                                            View
                                        </a>
                                        <button type="submit" id="delete-invoice-{{ $invoice->id }}" class="btn btn-danger">
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
            <button type="button" class="btn btn-default" data-toggle="collapse" data-target="#add" id="conBtn"><i class="fa fa-plus"></i> Add invoice</button>
        </div>
        <div class="row panel-body collapse" id="add">
        <!-- Display Validation Errors -->
        @include('common.errors')

        <!-- New Task Form -->
        <form action="{{ url('invoices/'.$contract->id) }}" method="POST" class="form-inline">
            {{ csrf_field() }}

            <!-- Task Name -->
            <div class="form-group">
                <div class="input-group">
                    <label for="due" class="col-sm-12">Due Date:</label>
                    <input type="date" class="form-control" id="due" name="due_date">
                </div>
            </div>

            <!-- Add Task Button -->
            <div class="form-group">
                <div align="center">
                    <button type="submit" class="btn btn-default" id="subBtn">
                        Submit
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
    @endif
@endsection