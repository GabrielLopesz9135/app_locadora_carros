<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClienteRequest;
use App\Models\Cliente;
use App\Repositories\ClienteRepository;
use Illuminate\Http\Request;


class ClienteController extends Controller
{
    private $model;
    private $repository;

    public function __construct(Cliente $model)
    {
        $this->model = $model;
        $this->repository = new ClienteRepository($this->model);
    }

    public function index(Request $request)
    {
       
        if($request->has('filtros')){
            $this->repository->filtros($request->filtros);
        }
        
        return response()->json($this->repository->getResult());
    }


    public function store(StoreClienteRequest $request)
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

    public function update(StoreClienteRequest $request, $id)
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
