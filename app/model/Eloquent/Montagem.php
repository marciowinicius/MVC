<?php

namespace app\model\Eloquent;
use app\model\Eloquent\Model as Model;

class Montagem extends Model
{
    /**Fillable do model
     * @var array
     */
    protected $fillable = ['codigo', 'ano_fabricacao', 'ano_modelo', 'cor', 'completo'];

    /**Tabela de referência do model
     * @var string
     */
    protected $table = 'montagem';

}
