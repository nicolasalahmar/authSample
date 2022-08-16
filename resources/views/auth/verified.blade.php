@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Your Email Address has been verified.') }}</div>

                    <!--<div class="card-body">
                        @if (session('resent'))
                            <div class="alert alert-success" role="alert">
                                {{ __('A fresh verification link has been sent to your email address.') }}
                            </div>
                        @endif-->

                         <h4>the email {{$email}} has been verified.</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
