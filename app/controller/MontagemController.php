<?php

namespace app\controller;

use app\model\Eloquent\Montagem;
use core\Controller;
use app\model\Eloquent\Datatable;
use Symfony\Component\VarDumper\Cloner\Data;

class MontagemController extends Controller
{

    /**Lista todos as montagens de carros.
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function index()
    {
        $montagens = Montagem::all()->all();
        $this->toRender('montagem/index.php', $montagens);
    }

    /**
     * DataTables de montagem
     */
    public function data()
    {
        $where = '';

        $table_data = new Datatable();

        // Get the data
        $data = $table_data->get('montagem', 'id', array('codigo', 'ano_fabricacao', 'ano_modelo', 'cor', 'completo', 'id'), $where, "", "montagem.id");

        echo json_encode($data);
        exit();
    }
}