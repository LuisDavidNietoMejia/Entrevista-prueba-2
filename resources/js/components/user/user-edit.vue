<template>
<modal name="user-modal-update" @before-open="beforeOpen" :adaptive="true" width="80%" height="390">

    <div class="panel-body col-md-12 col-sm-12 col-xs-12 p-5">

        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label">Nombre</label>
            <div class="col-sm-10">
                <input v-model="modelUser.name" type="text" class="form-control" placeholder="Escriba aqui su nombre">
            </div>
        </div>

        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label">Correo</label>
            <div class="col-sm-10">
                <input v-model="modelUser.email" type="email" class="form-control" placeholder="Escriba aqui su correo">
            </div>
        </div>

    </div>

    <div class="panel-footer">
        <div class="text-center">
            <input class="btn btn-primary" type="button" v-on:click="userUpdate()" value="Actualizar Usuario" />
            <input type="button" class="btn btn-danger" @click="$modal.hide('user-modal-update')" value="Cerrar" />
        </div>
    </div>

</modal>
</template>

<script>
import EventBus from '../event-bus';

export default {
    name: 'userUpdate',
    mounted() {
        console.log('Component mounted.')
    },
    data: function() {
        return {
            modelUser: {
                id: '',
                name: '',
                email: ''
            }
        }
    },
    methods: {
        beforeOpen: function(event) {
            this.modelUser.id = event.params.modelUser.id;
            this.modelUser.name = event.params.modelUser.name;
            this.modelUser.email = event.params.modelUser.email;
        },
        userUpdate: function() {
            axios.put(route('user.update', {
                    id: this.modelUser.id
                }), {
                    id: this.modelUser.id,
                    name: this.modelUser.name,
                    email: this.modelUser.email
                }).then(response => {
                    this.$modal.hide('user-modal-update');                  
                    toastr.success(response.data.message.success, response.data.status);
                    EventBus.$emit('user-modal-update');
                })
                .catch(error => {
                    if (error.response) {
                        if (error.response.status == 500) {
                            toastr.error('Ocurrio un error actualizando el usuario', 500);
                        } else if (error.response.status == 400) {
                            for (var field in error.response.data.message) {
                                toastr.error(error.response.data.message[field], 400);
                            }
                            this.loading = false;
                        }
                    } else {
                        toastr.error('Ocurrio un error actualizando el usuario', error.response.status);
                        this.loading = false;
                    }
                });
        },
    }
}
</script>

<style>


</style>
