<?php

namespace App\Http\Controllers;

use App\Models\Locacao;
use App\Http\Requests\StoreLocacaoRequest;
use App\Repositories\LocacaoRepository;
use Illuminate\Http\Request;

class LocacaoController extends Controller
{

    private $model;
    private $repository;

    public function __construct(Locacao $model)
    {
        $this->model = $model;
        $this->repository = new LocacaoRepository($this->model);
    }


    public function index(Request $request)
    {
       
        if($request->has('filtros')){
            $this->repository->filtros($request->filtros);
        }

        if($request->has('atributos')){
            $atributos = $request->atributos;
            $this->repository->AtributosModel($atributos);
        } 

        return response()->json($this->repository->getResult());
    }


    public function store(StoreLocacaoRequest $request)
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

    public function update(StoreLocacaoRequest $request, $id)
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
