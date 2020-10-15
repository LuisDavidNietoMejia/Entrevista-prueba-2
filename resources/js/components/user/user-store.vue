<template>
<modal name="user-modal-store" :adaptive="true" width="80%" height="390">

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

    <div class="col-md-12">
        <p>Se asignara una clave por defecto al usuario creado y se le enviara por correo</p>
        <p>Clave por defecto: user_documents</p>
    </div>

    <div class="panel-footer">
        <div class="text-center">
            <input class="btn btn-primary" type="button" v-on:click="userStore()" value="Crear Usuario" />
            <input type="button" class="btn btn-danger" @click="$modal.hide('user-modal-store')" value="Cerrar" />
        </div>
    </div>

</modal>
</template>

<script>
import EventBus from '../event-bus';

export default {
    name: 'userStore',
    mounted() {
        console.log('Component mounted.')
    },
    data: function() {
        return {
            modelUser: {
                id: '',
                name: '',
                last_name: '',
                email: ''
            }
        }
    },
    methods: {
        userStore: function() {
            axios.post(route('user.store'), {
                    name: this.modelUser.name,
                    last_name: this.modelUser.last_name,
                    email: this.modelUser.email,
                    fecha: this.modelUser.fecha
                }).then(response => {
                    this.$modal.hide('user-modal-store');
                    EventBus.$emit('user-modal-store');
                    toastr.success(response.data.message.success, response.data.status);
                })
                .catch(error => {
                    if (error.response) {
                        if (error.response.status == 500) {
                            toastr.error('Ocurrio un error registrando el usuario', 500);
                        } else if (error.response.status == 400) {
                            for (var field in error.response.data.message) {
                                toastr.error(error.response.data.message[field], 400);
                            }
                            this.loading = false;
                        }
                    } else {
                        toastr.error('Ocurrio un error registrando el usuario', error.response.status);
                        this.loading = false;
                    }
                });
        },
    }
}
</script>

<style>


</style>
