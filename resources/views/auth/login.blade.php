@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
            <div class="card card-signin my-5">
                    <div class="card-body">
                        <div class="card-title text-center">
                        <ul class="btn-group btn-group-toggle" data-toggle="buttons">
                            <li class=" btn btn-outline-primary active" id="btn1" >
                                <input type="radio" id="option1" autocomplete="off" checked name="aluno" value="aluno"
                                class="botao"> {{ __('Entrar como Aluno') }}
                            </li>
                            <li class=" btn btn-outline-primary" id="btn2" >
                                <input type="radio" dusk="option2" id="option2" autocomplete="off" name="funcionario" value="funcionario"> {{ __('Entrar como Funcionário') }}
                            </li>
                        </ul>
                    </div>
                     <form id="form" method="POST" action="{{ route('login') }}">
                            @csrf

                        <div class="form-group row">
                            <label for="email" class="col-md-3 col-form-label text-md-right">{{ __('E-mail') }}</label>
                            <div class="col-md-9">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                                   <div class="form-group row">
                            <label for="password" class="col-md-3 col-form-label text-md-right">{{ __('Senha') }}</label>

                            <div class="col-md-9">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    
                            <button type="submit" class="btn btn-primary btn-block">
                                 {{ __('Entrar') }}
                            </button>
                    
                    </form>
                </div>
            </div>


        </div>
    </div>
</div>

@endsection
