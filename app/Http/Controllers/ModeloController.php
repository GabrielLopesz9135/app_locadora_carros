<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreModeloRequest;
use App\Models\Modelo;
use App\Repositories\ModeloRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ModeloController extends Controller
{

    private $model;
    private $repository;

    public function __construct(Modelo $model)
    {
        $this->model = $model;
        $this->repository = new ModeloRepository($this->model);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
       
        if($request->has('atributos_marca')) {
            $atributos_marca = $request->atributos_marca;
            $this->repository->selectAtributosRelacionados('marca:id,'.$atributos_marca);
        }else{
            $this->repository->selectAtributosRelacionados('marca');
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreModeloRequest $request)
    {
        $imagem = $request->file('imagem');
        $path = $imagem->store('modelos');

        $data = $request->all();

        $data['imagem'] = $path;

        $data = $this->model->create($data);
        return $data;
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data = $this->model->with('marca')->find($id);
        if($data === null){
            return response()->json(['Erro' => 'Recurso Pesquisado não existe'], 404) ;
        }else{
            return $data;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreModeloRequest $request, $id)
    {
        $model = $this->model->find($id);

        if($model === null){
            return response()->json(['Erro' => 'Recurso Pesquisado não existe'], 404);
        }else{

            $data = $request->all();

            if($request->file('imagem')){
                $imagem = $request->file('imagem');
                $path = $imagem->store('modelos');
                $data['imagem'] = $path;
                Storage::disk('public')->delete($model->imagem);
            }
            
            $model->update($data);
            return response()->json($data, 200);
        } 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data = $this->model->find($id);
        if($data === null){
            return response()->json(['Erro' => 'Recurso Pesquisado não existe'], 404);
        }else{
            Storage::disk('public')->delete($data->imagem);
            $data->delete();
            return ['Mensagem' => 'Recurso apagado com sucesso'];
        }
    }
}
