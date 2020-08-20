@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Create Excel</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <h2>Enter Data</h2>
                    <form method="POST">
                    @csrf
                        
                        <div class="form-group">
                            <label for="ip"><b>IP:</b></label>
                            <input type="text" class="form-control" name="ip">
                        </div>
                        <div class="form-group">
                            <label for="domain"><b>Domain:</b></label>
                            <input type="text" class="form-control" name="domain">
                        </div>
                        
                        <button type="submit" formaction="{{ url('/createexcel') }}" class="btn btn-primary">Export Data</button>
                        

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
