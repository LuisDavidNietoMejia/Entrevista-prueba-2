<template>
<modal name="document-modal-update" @before-open="beforeOpen" :adaptive="true" width="80%" height="390">

    <div class="panel-body col-md-12 col-sm-12 col-xs-12 p-5">

        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label">Coloque contenido:</label>
            <div class="col-sm-10">
                <input v-model="modelDocument.content" type="text" class="form-control" placeholder="Escriba aqui el contenido">
            </div>
        </div>

        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label">Eliga un usuario:</label>
            <div class="col-sm-10">
                <vue-single-select style="width:100%;" name="users" placeholder="Eliga un usuario" v-model="modelDocument.user_id" :options="users" option-key="id" option-label="name" :required="true"></vue-single-select>
            </div>
        </div>

    </div>

    <div class="panel-footer">
        <div class="text-center">
            <input class="btn btn-primary" type="button" v-on:click="userUpdate()" value="Actualizar Documento" />
            <input type="button" class="btn btn-danger" @click="$modal.hide('document-modal-update')" value="Cerrar" />
        </div>
    </div>

</modal>
</template>

<script>
import EventBus from '../event-bus';

export default {
    name: 'documentUpdate',
    mounted() {
        console.log('Component mounted.')
        this.getUsersSelect();
    },
    data: function() {
        return {
            users: [],
            modelDocument: {
                id: '',
                content: '',
                user_id: ''
            }
        }
    },
    methods: {
        getUsersSelect: function(pageNo) {
            var url = route('users.select');
            axios.get(url)
                .then(response => {
                    this.users = response.data.result.users;
                })
                .catch(error => {
                    if (error.response) {
                        if (error.response.status == 500) {
                            toastr.error('Ocurrio un error mostrando los usuarios', 500);
                        } else if (error.response.status == 400) {
                            for (var field in error.response.data.message) {
                                toastr.error(error.response.data.message[field], 400);
                            }
                            this.loading = false;
                        }
                    } else {
                        toastr.error('Ocurrio un error consultando los usuarios', error.response.status);
                    }
                });
        },
        beforeOpen: function(event) {
            this.modelDocument.id = event.params.modelDocument.id;
            this.modelDocument.content = event.params.modelDocument.content;
            this.modelDocument.user_id = event.params.modelDocument.user_id
        },
        userUpdate: function() {
            axios.put(route('document.update', {
                    id: this.modelDocument.id
                }), {
                    id: this.modelDocument.id,
                    content: this.modelDocument.content,
                    user_id: this.modelDocument.user_id.id
                }).then(response => {
                    this.$modal.hide('document-modal-update');
                    toastr.success(response.data.message.success, response.data.status);
                    EventBus.$emit('document-modal-update');
                })
                .catch(error => {
                    if (error.response) {
                        if (error.response.status == 500) {
                            toastr.error('Ocurrio un error actualizando el documento', 500);
                        } else if (error.response.status == 400) {
                            for (var field in error.response.data.message) {
                                toastr.error(error.response.data.message[field], 400);
                            }
                            this.loading = false;
                        }
                    } else {
                        toastr.error('Ocurrio un error actualizando el documento', error.response.status);
                        this.loading = false;
                    }
                });
        },
    }
}
</script>

<style>


</style>
