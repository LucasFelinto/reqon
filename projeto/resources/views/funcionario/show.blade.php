@extends('layouts.app')


@section('content')
    <div class="container">
    <div class="container" id="breadcrumb">
            <span class="itemBread"><a href="/">Início</a> ></span>
            <span class="itemBread"><a href="/indexfunc">Requerimentos</a> ></span>
            <span class="breadcrumb-item active itemBread" aria-current="page">Detalhes do Requerimento</span>

    </div>
    </div>
    <div class="showreq">

        <div id="cont">
            <div class="container-fluid">
                @if($requerimento)
                @foreach ($requerimento as $req)

                <div class="row">
                    <div class="col-sm">
                          Protocolo
                        </div>
                        <div class="col-sm">
                            Matrícula
                        </div>
                        <div class="col-sm">
                            Nome do Aluno
                        </div>
                        <div class="col-sm">
                            Curso
                        </div>
                        <div class="col-sm">
                            Tipo
                        </div>
                        <div class="col-sm">
                          Data
                        </div>
                        <div class="col-sm">
                            Situação
                        </div>
                        <div class="col-sm">
                            Semestre
                        </div>
                        <div class="col-sm">
                          Status da matrícula
                        </div>
                        <div class="col-sm">
                            Setor
                        </div>
                        <div class="col-sm">
                          Descrição
                        </div>
                        <div class="w-100"></div>

                        <div class="col-sm">
                          {{$req['protocolo']}}
                        </div>
                        <div class="col-sm">
                            {{$req['matricula']['matricula']}}
                        </div>
                        <div class="col-sm">
                            {{$req->matricula->aluno->nome}}
                        </div>
                        <div class="col-sm">
                            {{$req->matricula->curso->nome}}
                        </div>
                        <div class="col-sm">
                            {{$req['subtipo']['descricao']}}
                        </div>
                        <div class="col-sm">
                          {{date('d-m-Y', strtotime($req['created_at']))}}
                        </div>
                        <div class="col-sm">
                            {{$req['status']['situacao']}}
                        </div>
                        <div class="col-sm">
                            {{$req['matricula']['semestre']}}
                        </div>
                        <div class="col-sm">
                            {{$req['matricula']['status']}}
                        </div>
                        <div class="col-sm">
                            {{$req['setor']['nome']}}
                        </div>
                        <div class="col-sm">
                            {{$req['descricao']}}
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>
        <hr class="my-4">
	    <div class="" id="cont">
             <div class="container-fluid">
                 @if($reqpai)
                    @foreach ($reqpai as $req)

                    <div class="row">

                        <div class="col-sm">
                          {{$req['protocolo']}}
                        </div>
                        <div class="col-sm">
                          {{$req['matricula']['matricula']}}
                        </div>
                        <div class="col-sm">
                            {{$req->matricula->aluno->nome}}
                        </div>
                        <div class="col-sm">
                          {{$req->matricula->curso->nome}}
                        </div>
                        <div class="col-sm">
                          {{$req['subtipo']['descricao']}}
                        </div>
                        <div class="col-sm">
                            {{date('d-m-Y', strtotime($req['created_at']))}}
                        </div>
                        <div class="col-sm">
                            {{$req['status']['situacao']}}
                        </div>
                        <div class="col-sm">
                            {{$req['matricula']['semestre']}}
                        </div>
                        <div class="col-sm">
                            {{$req['matricula']['status']}}
                        </div>
                        <div class="col-sm">
                            {{$req['setor']['nome']}}
                        </div>
                        <div class="col-sm">
                            {{$req['descricao']}}
                        </div>
                    </div>
                    @endforeach
                    @endif
            </div>
            <div class="row">
                <div class="form-group">
                    <select name="teste" class="selectpicker form-control" >
                        <option value="1">CRADT</option>
                        <option value="2">COORDENAÇÃO</option>
                        <option value="3">DAEECINFO</option>
                        <option value="4">CINFO</option>
                    </select>
                </div>
  </div>
</div>
<form action="{{ route('redirect') }}" method="post">
    @csrf
    <input type="text" name="teste">
    <button type="submit">Enviar</button>
</form>
  </div>
</div>
        </div>
@endsection
