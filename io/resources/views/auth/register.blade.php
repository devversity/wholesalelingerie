@extends('layout.app')
@section('page_title', 'Register')
@section('page_description', 'Register')

@section('content')

<div class="row">
    <div class="content-wrapper full-page-wrapper d-flex align-items-center auth login-full-bg">
        <div class="row w-100">
            <div class="col-lg-4 mx-auto">
                <div class="auth-form-dark text-left p-5">

                    <div class="mt-3 text-center">
                        <img src="/public/assets/images/logo.png" style="padding:10px; background:#eee">
                    </div>

                    <form method="POST" action="/auth/register" class="pt-5">
                        {!! csrf_field() !!}
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input name="name" class="form-control p-2" id="name" placeholder="Name">
                            <i class="mdi mdi-account"></i>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input name="email" type="email" class="form-control p-2" id="mail" aria-describedby="emailHelp" placeholder="Email">
                            <i class="mdi mdi-account"></i>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input name="password" type="password" class="form-control p-2" id="password" placeholder="Password">
                            <i class="mdi mdi-eye"></i>
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">Confirm Password</label>
                            <input name="password_confirmation" type="password" class="form-control p-2" id="password_confirmation" placeholder="Password">
                            <i class="mdi mdi-eye"></i>
                        </div>
                        <div class="mt-5">
                            <button class="btn btn-block btn-warning btn-lg font-weight-medium">Register</button>
                        </div>
                        <div class="mt-3 text-center">
                            <a href="/auth/login" class="auth-link text-white">Already a member?</a>
                        </div>
                        @if (count($errors))
                            @foreach($errors->all() as $error)
                                <div class="alert alert-fill-danger mt-2" role="alert">
                                    {{$error}}
                                </div>
                            @endforeach
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('script-footer')

@endpush

@endsection
