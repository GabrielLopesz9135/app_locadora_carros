<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCarroRequest;
use App\Models\Carro;
use Illuminate\Http\Request;
use App\Repositories\CarroRepository;

class CarroController extends Controller
{
    private $model;
    private $repository;

    public function __construct(Carro $model)
    {
        $this->model = $model;
        $this->repository = new CarroRepository($this->model);
    }

    public function index(Request $request)
    {
       
        if($request->has('atributos_modelo')) {
            $atributos_modelo = $request->atributos_modelo;
            $this->repository->selectAtributosRelacionados('modelo:id,'.$atributos_modelo);
        }else{
            $this->repository->selectAtributosRelacionados('modelo');
        }

        if($request->has('filtros')){
            $this->repository->filtros($request->filtros);
        }

        if($request->has('atributos')){
            $atributos = $request->atributos;
            $this->repository->AtributosModel($atributos);
        } 

        return response()->json($this->repository->getResult());
    }


    public function store(StoreCarroRequest $request)
    {
        $data = $request->all();
        $data = $this->model->create($data);
        return response()->json($data);
    }

    public function show($id)
    {
        $data = $this->model->with('modelo')->find($id);
        if($data === null){
            return response()->json(['Erro' => 'Recurso Pesquisado não existe'], 404) ;
        }else{
            return $data;
        }
    }

    public function update(StoreCarroRequest $request, $id)
    {
        $model = $this->model->find($id);

        if($model === null){
            return response()->json(['Erro' => 'Recurso Pesquisado não existe'], 404);
        }else{

            $data = $request->all();
            $model->update($data);
            return response()->json($data, 200);
        } 
    }

    public function destroy($id)
    {
        $data = $this->model->find($id);
        if($data === null){
            return response()->json(['Erro' => 'Recurso Pesquisado não existe'], 404);
        }else{
            $data->delete();
            return ['Mensagem' => 'Recurso apagado com sucesso'];
        }
    }
}
