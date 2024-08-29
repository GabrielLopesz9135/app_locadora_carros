<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractRepository{

    private $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function selectAtributosRelacionados($atributos){
        $this->model = $this->model->with($atributos);
    }

    public function filtros($params){
        $filtros = explode(',', $params);
            foreach($filtros as $filtro){
                $params = explode(':', $filtro);
                $this->model = $this->model->where($params[0], $params[1], $params[2]);
            } 
    }

    public function AtributosModel($atributos){
        $this->model = $this->model->selectRaw($atributos);
    }

    public function getResult(){
        return $this->model->get();
    }
}
