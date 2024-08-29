<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMarcaRequest;
use App\Models\Marca;
use App\Repositories\MarcaRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MarcaController extends Controller
{
    private $marca;
    private $repository;

    public function __construct(Marca $marca)
    {
        $this->marca = $marca;
        $this->repository = new MarcaRepository($this->marca);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        if($request->has('atributos_modelos')) {
            $atributos_modelos = $request->atributos_modelos;
            $this->repository->selectAtributosRelacionados('modelos:id,'.$atributos_modelos);
        }else{
            $this->repository->selectAtributosRelacionados('modelos');
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
    public function store(StoreMarcaRequest $request)
    {
        $imagem = $request->file('imagem');
        $path = $imagem->store('imagens');

        $data = $request->all();

        $data['imagem'] = $path;

        $marca = $this->marca->create($data);
        return $marca;
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $marca = $this->marca->find($id);
        if($marca === null){
            return response()->json(['Erro' => 'Recurso Pesquisado não existe'], 404) ;
        }else{
            return $marca;
        }
        
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function update(StoreMarcaRequest $request, $id)
    {
        $marca = $this->marca->find($id);

        if($marca === null){
            return response()->json(['Erro' => 'Recurso Pesquisado não existe'], 404);
        }else{
            $imagem = $request->file('imagem');
            $path = $imagem->store('imagens');

            $data = $request->all();

            $data['imagem'] = $path;

            if($request->file('imagem')){
                Storage::disk('public')->delete($marca->imagem);
            }
            
            $marca->update($data);
            return response()->json($marca, 200);
        }        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $marca = $this->marca->find($id);
        if($marca === null){
            return response()->json(['Erro' => 'Recurso Pesquisado não existe'], 404);
        }else{
            Storage::disk('public')->delete($marca->imagem);
            $marca->delete();
            return ['Mensagem' => 'Recurso apagado com sucesso'];
        }
        
    }
}
