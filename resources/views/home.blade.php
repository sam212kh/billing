@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if ( session()->has('error') )
                        <div class="alert alert-danger alert-dismissable">{{ session()->get('error') }}</div>
                    @endif
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    Welcome, {{ Auth::user()->name }} <br />

                    @if ( $user->subscription('monthly')->onTrial())
                      <br />
                      you are on trial, its end : {{ date('Y-m-d', strtotime($user->trial_ends_at))  }}
                      <br />

                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
