
<div class="showreq child">

    <div class="child-header d-flex justify-content-between">
    <div class="icon @if($open) collapse @else expand @endif" data-body="#body-{{$requerimento->id}}"></div>
        <div class="p-1">
            <span class="setor">{{$requerimento['setor']['nome']}}</span>
            <span class="date">({{date('d-m-Y', strtotime($requerimento['created_at']))}})</span>
        </div>
        <div class="p-1">
            <span class="protocol">#{{$requerimento['protocolo']}}</span>
            <span class="status-bol status-{{Str::slug($requerimento['status']['situacao'])}}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
            <span class="status badge">{{$requerimento['status']['situacao']}}</span>
        </div>
    </div>
    <div class="child-body container-flex" id="body-{{$requerimento->id}}" @if(!$open) style="display:none" @endif>
        <p class="p-1">
            {{$requerimento['descricao']}}
        </p>
        @if($requerimento->comentario)
            <p class="answer p-1">
                {{$requerimento->comentario}}
            </p>
        @endif
    @if(isset($setorfunc))
        @foreach ($setorfunc as $stf)
            @if(Auth::guard('funcionario')->check() && $stf->setor_id == $requerimento['setor']['id'])
                <div class="row">
                    @if ($requerimento['status']['situacao'] != 'Deferido' && $requerimento['status']['situacao'] != 'Indeferido' && $requerimento['status']['situacao'] != 'Parcialmente Deferido')
                        <div class="col-4">
                            <button class="btn-action btn btn-block btn-primary btn-size" data-form="#encaminhar-{{$requerimento->id}}">Encaminhar</button>
                        </div>
                    @else
                        <div class="col-4">
                            <button class="btn-action btn btn-block btn-primary btn-size disabled" data-form="#encaminhar-{{$requerimento->id}}" aria-disabled="true">Encaminhar</button>
                        </div>
                    @endif

                    @if ($requerimento['status']['situacao'] == "Deferido" || $requerimento['status']['situacao'] == "Indeferido" || $requerimento['status']['situacao'] == "Parcialmente Deferido")
                        <div class="col-4">
                            <button class="btn-action btn btn-block btn-secondary btn-size disabled" data-form="#responder-{{$requerimento->id}}" aria-disabled="true">Responder</button>
                        </div>
                    @else
                        <div class="col-4">
                            <button class="btn-action btn btn-block btn-secondary btn-size" data-form="#responder-{{$requerimento->id}}">Responder</button>
                        </div>
                    @endif

                    @if ($requerimento['status']['situacao'] == "Criado" || $requerimento['status']['situacao'] == "Em Andamento")
                        <div class="col-4">
                            <button class="btn-action btn btn-block btn-secondary btn-size disabled" data-form="#reabrir-{{$requerimento->id}}" aria-disabled="true">Reabrir</button>
                        </div>
                    @else
                        <div class="col-4">
                            <button class="btn-action btn btn-block btn-secondary btn-size" data-form="#reabrir-{{$requerimento->id}}">Reabrir</button>
                        </div>
                    @endif

                </div>
                @if ($setor_nome != Auth::user()->nome)
                        @if ($requerimento['status']['situacao'] != "Deferido" && $requerimento['status']['situacao'] != "Indeferido" && $requerimento['status']['situacao'] != "Parcialmente Deferido")
                            <div class="form-action" id="responder-{{$requerimento->id}}" style="display: none">
                                <h3 class="subTitleReq">Responder</h3>
                                <div class="">
                                    <form action="{{ route('resposta') }}" method="post">
                                        @csrf
                                        <div class="row">
                                            <div class="form-group col">
                                                <select name="teste" class="selectpicker form-control" >
                                                    <option value="" selected>Selecione um Status</option>
                                                    @foreach ($status as $set)
                                                        @if ($set['situacao'] != 'Criado' && $set['situacao'] != 'Em Andamento')
                                                            <option value="{{$set['id']}}">{{$set['situacao']}}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>

                                            <input type="hidden" name="requerimento" value="{{$requerimento['id']}}">
                                            <input type="hidden" name="matricula" value="{{$requerimento['matricula']['id']}}">
                                            <input type="hidden" name="subtipo" value="{{$requerimento['subtipo']['id']}}">
                                            <input type="hidden" name="status" value="{{$requerimento['status']['id']}}">
                                            <input type="hidden" name="setor" value="{{$requerimento['setor']['id']}}">
                                            <input type="hidden" name="descricao" value="{{$requerimento['descricao']}}">

                                        </div>
                                        <div class="row">
                                            <div class="input-group col">
                                                <div class="input-group-prepend">
                                                    <button class="btn cmt" type="button" id="button-addon1">Comentário</button>
                                                </div>
                                                <textarea class="form-control comentario" name="comentario" aria-label="Comentario">{{old('comentario')}}</textarea>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <button type="submit" class="coment-btn btn-lg col col-sm-2">Enviar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endif
                    @if ($requerimento['status']['situacao'] != "Criado" && $requerimento['status']['situacao'] != "Em Andamento")
                        <p>{{$requerimento['status']['situacao']}}</p>
                        <div class="form-action" id="reabrir-{{$requerimento->id}}" style="display: none">
                            <h3 class="subTitleReq">Editar</h3>
                            <div class="">
                                <form action="{{ route('reabrir') }}" method="post">
                                    @csrf
                                    <div class="row">
                                        <input type="hidden" name="requerimento" value="{{$requerimento['id']}}">
                                        <input type="hidden" name="matricula" value="{{$requerimento['matricula']['id']}}">
                                        <input type="hidden" name="subtipo" value="{{$requerimento['subtipo']['id']}}">
                                        <input type="hidden" name="status" value="{{$requerimento['status']['id']}}">
                                        <input type="hidden" name="setor" value="{{$requerimento['setor']['id']}}">
                                        <input type="hidden" name="descricao" value="{{$requerimento['descricao']}}">

                                    </div>
                                    <div class="row">
                                        <div class="input-group col">
                                            <div class="input-group-prepend">
                                                <button class="btn cmt" type="button" id="button-addon1">Comentário</button>
                                                </div>
                                                <textarea class="form-control comentario" name="comentario" aria-label="Comentario">{{$requerimento->comentario}}</textarea>
                                            </div>
                                    </div>
                                    <div class="row">
                                        <button type="submit" class="coment-btn btn-lg col col-sm-2">Enviar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                    @if ($requerimento['status']['situacao'] != 'Deferido' && $requerimento['status']['situacao'] != 'Indeferido' && $requerimento['status']['situacao'] != 'Parcialmente Deferido')
                        <div class="form-action" id="encaminhar-{{$requerimento->id}}" style="display: none">
                            @method('Symfony\Component\Console\Input\Input')
                            <h3 class="subTitleReq">Encaminhar</h3>
                            <div class="">
                                <form action="{{ route('redirecionar') }}" method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="form-group col">
                                            <select name="teste" class="selectpicker form-control" data-live-search="true">
                                                <option value="" selected>Selecione um Setor</option>
                                                <optgroup label="Setores" data-max-options="2">
                                                @foreach ($setor as $set)
                                                    @if($setorfunc)
                                                    @foreach ($setorfunc as $setfunc)
                                                        @if ($set['id'] != $setfunc['setor_id'])
                                                            <option value="{{$set['id']}}">{{$set['nome']}}</option>
                                                        @endif
                                                    @endforeach
                                                    @endif
                                                @endforeach
                                                </optgroup>
                                                <optgroup label="Professores" data-max-options="2">
                                                @foreach ($professor as $prof)
                                                    <option value="{{$prof['id']}}">{{$prof['nome']}}</option>
                                                @endforeach
                                                </optgroup>
                                            </select>
                                        </div>

                                        <input type="hidden" name="requerimento" value="{{$requerimento['id']}}">
                                        <input type="hidden" name="matricula" value="{{$requerimento['matricula']['id']}}">
                                        <input type="hidden" name="subtipo" value="{{$requerimento['subtipo']['id']}}">
                                        <input type="hidden" name="status" value="{{$requerimento['status']['id']}}">
                                        <input type="hidden" name="setor" value="{{$requerimento['setor']['id']}}">
                                        <input type="hidden" name="descricao" value="{{$requerimento['descricao']}}">

                                    </div>
                                    <div class="row">
                                        <div class="input-group col">
                                            <div class="input-group-prepend">
                                                <button class="btn cmt" type="button" id="button-addon1">Comentário</button>
                                                </div>
                                                <textarea class="form-control comentario" name="comentario" aria-label="Comentario">{{old('comentario')}}</textarea>
                                            </div>
                                    </div>
                                    <div class="row">
                                        <button type="submit" class="coment-btn btn-lg col col-sm-2">Enviar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                @endif

            @endif
        @endforeach
    @endif
        @foreach ($requerimento->children as $requerimento)
            @php
                $open = false
            @endphp

            @if(Auth::guard('funcionario')->check())
                @include('det', [$requerimento, $status, $setor, $setorfunc, $open])
            @elseif(Auth::guard()->check())
                @include('det', [$requerimento, $status, $setor, $open])
            @endif
        @endforeach
    </div>
</div>
