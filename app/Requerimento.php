<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Requerimento extends Model
{
    protected $table = 'requerimentos';

    protected $fillable = [
            'protocolo',
            'descricao',
            'subtipo_id',
            'status_id',
            'req_pai_id',
            'funcionario_id',
            'setor_id',
            'matricula_id',
            'comentario'
    ];

    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];

    public function matricula(){
        return $this->belongsTo('App\Matricula');
    }
    public function funcionario(){
        return $this->belongsTo('App\Funcionario');
    }
    public function subtipo(){
        return $this->belongsTo('App\Subtipo');
    }

    public function status() {
        return $this->belongsTo('App\Status');
    }
    public function setor() {
        return $this->belongsTo('App\Setor');
    }
    public function children(){
        return $this->hasMany('App\Requerimento', 'req_pai_id', 'id');
    }

}
