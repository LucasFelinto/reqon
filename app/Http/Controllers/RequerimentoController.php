<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Setor;
use App\Requerimento;
use App\Subtipo;
use App\Tipo;
use App\Aluno;
use App\Matricula;
use App\Curso;
use App\SetorFuncionario;
use App\Status;
use App\Funcionario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use SebastianBergmann\CodeCoverage\Node\Builder;

class RequerimentoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

  public function resposta(request $request){

    //dd($request->all());
        $validator = Validator::make($request->all(), [
            'subtipo'=>'required|numeric|min:1',
            'status' =>'required|numeric|min:1',
            'setor' => 'required|numeric|min:1',
            'requerimento' => 'required|numeric|min:1',
            'matricula' => 'required|numeric|min:1',
            'teste' => 'required|numeric|min:1',
            'comentario' =>'required|min:4|max:100',
            'descricao' => 'required|min:1|max:100'

        ]);
        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();

        }
        $update = Requerimento::where('id', $request->get('requerimento'))
        ->update(['status_id' => $request->get('teste'),
        'funcionario_id' => Auth::user()->id, 'comentario'=> $request->get('comentario')]);
        //->update(['status_id' => $request->get('teste')])
        //->update(['funcionario_id' => Auth::user()->id])
        //->update(['comentario'=> $request->get('comentario')]);


        return redirect()->back()->withSuccess('Requerimento Respondido');
    }


    public function redirecionar(Request $request){
        //var_dump('derasdad');
        //dd($request->all());
        $validator = Validator::make($request->all(), [
            'subtipo'=>'required|numeric|min:1',
            'status' =>'required|numeric|min:1',
            'setor' => 'required|numeric|min:1',
            'requerimento' => 'required|numeric|min:1',
            'matricula' => 'required|numeric|min:1',
            'teste' => 'required|numeric|min:1',
            'comentario' =>'required|min:4|max:100',
            'descricao' => 'required|min:1|max:100'

        ]);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $reqback = Requerimento::where('id',$request->get('requerimento'))->where('setor_id', $request->get('teste'))->get();
        $reqret = Requerimento::where('req_pai_id', $request->get('requerimento'))->where('setor_id', $request->get('teste'))->get();
        $setorfunc = SetorFuncionario::where('funcionario_id',Auth::user()->id)->get();
        //dd($reqback, $request->all(), $setorfunc, $reqret);
        //dd(sizeof($reqback));
        if(sizeof($reqback) > 1 || sizeof($reqret) > 1){
            //dd("testees");
            return redirect()->back()->withErrors('Não e possivel encaminhar para o mesmo setor varias vezes');
        }

        foreach($setorfunc as $st){
            if($st->setor_id == $request->get('teste')){
                //dd('entrou');
                return redirect()->back()->withErrors('Não e possivel mandar para seu proprio setor');
            }
        }

        $req = new Requerimento([
            'protocolo' => mt_rand(1,999999999),
            'subtipo_id' => $request->get('subtipo'),
            //'descricao' => $request->get('descricao'),
            'status_id' => 4,
            'req_pai_id' => $request->get('requerimento'),
            'funcionario_id' => auth()->user()->id,
            'setor_id' => $request->get('teste'),
            'matricula_id' => $request->get('matricula'),
            //'comentario' => $request->get('comentario')
            'descricao' =>  $request->get('comentario'),
        ]);
        $req->save();

        return redirect()->back()->withSuccess('Requerimento Criado');
        //return redirect()->route('showreqfunc')->withSuccess('Requerimento Criado');
    }

    public function index(){
        $matriculas = Auth::user()->matriculas->sort(function($m1, $m2) {
            return strcmp($m1->curso->nome, $m2->curso->nome);
        });

        /*$matriculas = usort($matriculas, function($m1, $m2) {
            return strcmp($m1->curso->nome, $m2->curso->nome);
        });*/
        //dd($matriculas[0]['id']);
        if(sizeof($matriculas) > 1){
            $dados = Requerimento::with('subtipo')->where('matricula_id',$matriculas[0]['id'])->orWhere('matricula_id', $matriculas[1]['id'])->orderby('id', 'desc')->paginate(5);
        }else{
            $dados = Requerimento::with('subtipo')->where('matricula_id',$matriculas[0]['id'])->orderby('id', 'desc')->paginate(5);
        }
        //dd($dados);
        $test = Tipo::with('subtipos')->get();
        $status = Status::all();
        //dd($status);
        return view('requerimento.index',compact('matriculas','dados', 'status'));
    }

    public function search(Request $request){

        $matriculas = Auth::user()->matriculas->sort(function($m1, $m2) {
            return strcmp($m1->curso->nome, $m2->curso->nome);
        });

        $status = Status::all();

        $input = $request->all();
        // dd($input);
        $situacao = $input['situacao'];
        $protocolo = $input['protocolo'];
        $data_ini = $input['data_ini'];
        $data_fin = $input['data_fin'];
        $matri = $request->get('curso');
        $m = Matricula::where('matricula', $matri)->get();
        foreach($m as $mt){
            $matri_id = $mt['id'];
        }
        $exemplo = Requerimento::where('protocolo', '-1')->get();
        $situ = Status::where('id', $situacao)->get();
        foreach($situ as $st){
            $sit_id = $st['id'];
        }

        if($matri && empty($matri)){
           $matricula = Matricula::find(1)->where('matricula',$matri)->get();
           $dados = Requerimento::find(1)->where('matricula_id',$matricula[0]->id)->get();
        }

        if($situacao == "Selecione uma Situação" && $protocolo == null){

            if ($request->get('data_ini') && $request->get('data_ini')) {
                $validator = Validator::make($request->all(), [
                    'data_ini' => 'date',
                    'data_fin' => 'date'
                ]);

                if(!($validator->fails())){
                    $dados = Requerimento::whereBetween('created_at', [date($data_ini), date($data_fin)])
                                        ->where('matricula_id',$matriculas[0]['id'])
                                        ->orderby('id', 'desc')
                                        ->paginate(5);
                }else{
                    $dados = $exemplo;
                }
                if($exemplo == $dados){
                    // dd("1");
                    if($request->get('data_fin')){
                        $validator = Validator::make($request->all(), [
                            'data_ini' => 'date',
                        ]);
                        $dados = Requerimento::whereDate('created_at', '=', date($request->get('data_ini')))
                        ->where('matricula_id',$matriculas[0]['id'])->orderby('id', 'desc')->paginate(5);
                    }

                    if($request->get('data_ini')){
                        $validator = Validator::make($request->all(), [
                            'data_fin' => 'date',
                        ]);
                        $dados = Requerimento::whereDate('updated_at','=', date($request->get('data_fin')))
                        ->where('matricula_id',$matriculas[0]['id'])->orderby('id', 'desc')->paginate(5);
                         //dd("dados");

                    }

                    if($validator->fails()){
                        return redirect('/requerimento?src=Insira+a+data+corretamente')
                                ->withErrors($validator)
                                ->withInput();
                    }
                    return view('requerimento.index',compact('dados', 'status', 'matriculas'));
                }else{
                    return view('requerimento.index',compact('dados', 'status', 'matriculas'));
                    exit();
                }
            }else{
                return redirect('/requerimento?src=Insira+a+data+corretamente')
                                ->withInput();
            }
        }elseif($protocolo !=null || $situacao != "Selecione uma Situação"){

            if($request->get('situacao') || $request->get('protocolo')){
                $validator = Validator::make($request->all(), [
                    'protocolo'=>'required|numeric|min:1',
                    'situacao'=>'numeric|min:1'
                    ]);
                    if($situacao != "Selecione uma Situação" && $protocolo != null){

                        $dados = Requerimento::where('protocolo', $protocolo)
                        ->where('status_id', $sit_id)
                        ->where('matricula_id',$matriculas[0]['id'])
                        ->paginate(5);
                    }else{
                        $dados = $exemplo;
                    }
                if($exemplo == $dados){
                    if($request->get('situacao') != "Selecione uma Situação"){
                        $validator = Validator::make($request->all(), [
                            'situacao'=>'numeric|min:1',
                        ]);
                        $dados = Requerimento::where('status_id', $request->get('situacao'))
                        ->where('matricula_id',$matriculas[0]['id'])->orderby('id', 'desc')->paginate(5);
                    }
                    if($request->get('protocolo')){
                        $validator = Validator::make($request->all(), [
                            'protocolo'=>'required|numeric|min:1'
                        ]);
                        $dados = Requerimento::where('protocolo', $protocolo)
                        ->where('matricula_id',$matriculas[0]['id'])->orderby('id', 'desc')->paginate(5);
                        // dd('deburguer');
                    }
                    return view('requerimento.index',compact('dados', 'status', 'matriculas'));
                }else{
                    return view('requerimento.index',compact('dados', 'status', 'matriculas'));
                    exit();
                }
            }

        }

        if(!empty($matri_id)){
            $dados = Requerimento::where('matricula_id', $matri_id)->paginate(5);
            return view('requerimento.index',compact('dados', 'status', 'matriculas'));

        }

        $validator = Validator::make($request->all(), [
            'data_fin' => 'date',
        ]);

        if ($validator->fails()) {
            return redirect('/requerimento?src=Selecione+um+filtro')
                        ->withErrors($validator)
                        ->withInput();
        }

        /*return view('requerimento.index', compact('dados', 'matriculas', 'status'));

        $dados = Requerimento::where('descricao', $tipo)->get();
        return view('requerimento.index', compact('dados'));*/
    }

    public function create(Request $request){

        $tipo= Tipo::all();
        $curso = $request->get('curso');

        $matriculas = Auth::user()->matriculas->sort(function($m1, $m2) {
            return strcmp($m1->curso->nome, $m2->curso->nome);
        });
        //dd($matriculas);
        return view('requerimento.create', compact('tipo','curso','matriculas') );
    }

    public function store(Request $request){

        //dd($request->get('crs'));
        if($request->get('crs')){
            $validator = Validator::make($request->all(), [
                'crs'=>'required|numeric|min:1'
            ]);
            if ($validator->fails()) {
                //dd($validator);
                return redirect('/requerimento/create?crs=Selecione+Um+curso')
                            ->withErrors($validator)
                            ->withInput();
            }

            $matricula = Auth::user()->matriculas->where('curso_id', $request->get('crs'))->where('aluno_id', Auth::user()->id)->first();
            //dd($matricula);
        }
        else{
            $matricula = Auth::user()->matriculas->first();
        }
        $setor = Setor::find(1);
        $str = $setor->id;
        $mtr = $matricula->id;


        $validator = Validator::make($request->all(), [
            'descricao'=>'required',
            'subtipo'=>'required'
        ]);

        if ($validator->fails()) {
            return redirect('/requerimento/create?curso=Selecione+Um+curso')
                        ->withErrors($validator)
                        ->withInput();
        }

        $req = new Requerimento([
            'protocolo' => mt_rand(1,999999999),
            'descricao' => $request->get('descricao'),
            'subtipo_id' => $request->get('subtipo'),
            'status_id' => 5,
            'req_pai_id' => null,
            'funcionario_id' => null,
            'setor_id' => $str,
            'matricula_id' => $mtr
        ]);

        $req->save();

        return redirect()->route('requerimento.index')->withSuccess('Requerimento criado com sucesso!');
    }

    public function show($id){
        $setor = Setor::all();
        $requerimento = Requerimento::find($id);
        //dd($requerimento->id);
        $reqpai = Requerimento::find(1)->where('req_pai_id', $requerimento->id)->get();
        $status = Status::all();
        $professor = Funcionario::where('cargo', 'Professor')->get();
        //dd($reqpai);
        return view('fulldet', compact('requerimento', 'status', 'setor', 'professor'));
    }

    public function update(Request $request){

    }



}
