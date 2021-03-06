<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subtipo extends Model
{
    protected $table = 'subtipos';

    protected $fillable = [
        'descricao',
        'tipo_id'
    ];
    public function requerimento(){
        return $this->hasMany('App\Requerimento');
    }
    public function tipo(){
        return $this->hasMany('App\Tipo');
    }
}
