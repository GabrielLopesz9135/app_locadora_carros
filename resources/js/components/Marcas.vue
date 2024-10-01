<template>
    <div class="container mb-3">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <card-component title="Filtros Marcas">
                <template v-slot:content>
                    <div class="mb-3">
                        <inputContainer-component titulo="Nome da Marca" id="marcaInput" idHelp="marcaHelp" helpText="Insira o nome da marca">
                            <input type="text" class="form-control" id="marcaInput" aria-describedby="marcaHelp">
                        </inputContainer-component>
                    </div> 
                </template>

                <template v-slot:footer>
                    <button type="submit" class="btn btn-primary ">Pesquisar</button> 
                </template>
            </card-component>
        </div>
    </div>
</div>

<!-- card de listagem -->
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <card-component title="Lista de Marcas">
                <template v-slot:content>
                    <table-component></table-component>
                </template>

                <template v-slot:footer>
                    <button data-bs-toggle="modal" data-bs-target="#modalMarca" type="submit" class="btn btn-success">Adicionar Nova Marca</button>
                </template>
            </card-component>


        </div>
    </div>

        <modal-component id="modalMarca" title="Adicionar Marca">

            <template v-slot:content>
                <div class="form-group mb-3">
                    <inputContainer-component titulo="Nome da Marca" id="createMarcaNome" idHelp="createMarcaHelpNome" helpText="Insira o nome da marca">
                        <input type="text" class="form-control" id="createMarcaNome" aria-describedby="createMarcaHelpNome" v-model="name">
                    </inputContainer-component>
                </div>

                <div class="form-group">
                    <inputContainer-component titulo="Logo da Marca" id="createMarcaImage" idHelp="createMarcaHelpLogo" helpText="Insira o logo da marca">
                        <input type="file" class="form-control" id="createMarcaImage" aria-describedby="createMarcaHelpLogo" @change="loadImage($event)">
                    </inputContainer-component>
                </div>
            </template>

            <template v-slot:footer>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" @click="save()">Salvar</button>
            </template>
        </modal-component>
</div>
</template>

<script>
import axios from 'axios';

    export default {
        data(){
            return {
                urlBase: "http://localhost:8000/api/v1/marcas",
                name: null,
                image: []
            }
        },
        methods: {
            loadImage(e){
                this.image = e.target.files;
            },
            save(){
                console.log(this.name, this.image)

                let formData = new FormData();
                formData.append('nome', this.name);
                formData.append('imagem', this.image[0])

                let config = {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'Accept': 'application/json'
                    }
                }

                axios.post(this.urlBase, formData, config)
                    .then(response => { 
                        console.log(response)
                    })
                    .catch(errors => {
                        console.log(errors)
                    })
            }
        }
    }
</script>
