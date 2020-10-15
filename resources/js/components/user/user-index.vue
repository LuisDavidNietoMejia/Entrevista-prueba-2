<template>
<div class="panel">
    <hr>
    <div class="panel-heading text-center">

        <userStore></userStore>
        <userEdit></userEdit>
        <div class="row">
            <div class="col-lg-4 col-sm-6 col-md-6 col-xs-12">
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon">
                        </span>
                        <select v-on:change="onChangeSearch($event,null)" v-model="orderBy.count" class="form-control nice-select" name="countSearch">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <span class="input-group-addon px-3">
                            entradas
                        </span>
                    </div>
                </div>

            </div>
            <div class="col-lg-4 col-sm-6 col-md-6 col-xs-12">
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon px-3">
                            Buscar
                            <i class="glyphicon glyphicon-search"></i>
                        </span>
                        <input v-on:input="onChangeSearch($event,null)" v-model="orderBy.search" type="text" class="form-control" name="fieldSearch" required></div>
                </div>
            </div>

            <div class="col-lg-4 col-sm-6 col-md-6 col-xs-12 px-3">
                <a class="btn btn-primary" v-on:click="showStore()">Crear Nuevo Usuario</a>
            </div>

        </div>
    </div>
    <br>

    <vue-element-loading :active="isLoading" spinner="bar-fade-scale" color="#0051FF" text="Espere por favor..." />
    <div class="panel-body">
        <div class='table-responsive'>
            <table id="myTable2" class='table table-striped table-bordered table-hover table-condensed'>
                <thead>
                    <tr class="myHead">

                        <th v-on:click="getUsersIndexOrder('users.name',datatable.currentPage)">
                            Nombre<i v-if="orderBy.field == 'users.name'" :class="cssClassOrder()"></i>
                        </th>

                        <th v-on:click="getUsersIndexOrder('users.email',datatable.currentPage)">
                            Email<i v-if="orderBy.field == 'users.email'" :class="cssClassOrder()"></i>
                        </th>

                        <th>Editar</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="user in users" v-bind:key="user.id">
                        <td>{{ user.name }}</td>
                        <td>{{ user.email }}</td>
                        <td>
                            <a v-on:click="showUpdate(user)">
                                <i class="fal fa-user-edit"></i>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="text-center">
                <ul class="pagination">
                    <li :class="[cssClassNextPrev(datatable.prevPageUrl),'page-item']">
                        <a @click="getUsersPageNextPrev(datatable.prevPageUrl)" class="page-link">Previous</a>
                    </li>
                    <li :class="cssClassPage(pageNo)" :key="pageNo" v-for="pageNo in datatable.totalPages">
                        <a @click="getUsersIndex(pageNo)" class="page-link" href="#">{{ pageNo }}</a>
                    </li>
                    <li :class="[cssClassNextPrev(datatable.nextPageUrl),'page-item']">
                        <a @click="getUsersPageNextPrev(datatable.nextPageUrl)" class="page-link">Next</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
</template>

<script>
import userStore from './user-store';
import userEdit from './user-edit';
import EventBus from '../event-bus';

var moment = require('moment');

export default {
    mounted() {
        console.log('Component mounted user store.')
        this.getUsersIndex(this.datatable.currentPage);
        EventBus.$on('user-modal-store', dataArray => {
            this.getUsersIndex(this.datatable.currentPage);
        })
        EventBus.$on('user-modal-update', dataArray => {
            this.getUsersIndex(this.datatable.currentPage);
        })
    },
    created: function() {
        console.log('Component created user store.')
    },
    data: function() {
        return {
            isLoading: false,
            users: [],
            datatable: {
                nextPageUrl: '',
                prevPageUrl: '',
                totalPages: '',
                currentPage: ''
            },
            fillUser: {
                id: '',
                name: '',
                email: ''
            },
            moment: moment,
            orderBy: {
                field: 'users.name',
                status: 'activo',
                order: 'asc',
                count: '5',
                search: ''
            }
        }
    },
    components: {
        userStore,
        userEdit
    },
    methods: {
        onChangeSearch(event, pageNo) {
            this.isLoading = true;
            if (event.target.name == 'countSearch') {
                this.orderBy.search = "";
            }
            var url = route('user.datatable');
            url = url.concat("?page=");
            url = url.concat(pageNo);

            axios.get(url, {
                    params: {
                        field: this.orderBy.field,
                        status: this.orderBy.status,
                        order: this.orderBy.order,
                        count: this.orderBy.count,
                        search: this.orderBy.search
                    }
                })
                .then(response => {
                    this.users = response.data.result.users.data;
                    this.datatable.nextPageUrl = response.data.result.users.next_page_url;
                    this.datatable.prevPageUrl = response.data.result.users.prev_page_url;
                    this.datatable.totalPages = response.data.result.users.last_page;
                    this.datatable.currentPage = response.data.result.users.current_page;
                    this.isLoading = false;
                })
                .catch(error => {
                    this.loading = false;
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
                        toastr.error('Ocurrio un error mostrando los usuarios', error.response.status);
                    }
                });
        },
        showStore: function() {
            this.$modal.show('user-modal-store');
        },
        showUpdate: function(user) {
            this.$modal.show('user-modal-update', {
                  modelUser: user
              })
        },
        hide: function() {
            this.$modal.hide('user-modal-store')
            this.fillUser = []
        },
        getUsersIndex: function(pageNo) {
            this.isLoading = true;

            var url = route('user.datatable');
            url = url.concat("?page=");
            url = url.concat(pageNo);

            axios.get(url, {
                    params: {
                        field: this.orderBy.field,
                        status: this.orderBy.status,
                        order: this.orderBy.order,
                        count: this.orderBy.count,
                        search: this.orderBy.search
                    }
                })
                .then(response => {
                    this.users = response.data.result.users.data;
                    this.datatable.nextPageUrl = response.data.result.users.next_page_url;
                    this.datatable.prevPageUrl = response.data.result.users.prev_page_url;
                    this.datatable.totalPages = response.data.result.users.last_page;
                    this.datatable.currentPage = response.data.result.users.current_page;
                    this.isLoading = false;
                })
                .catch(error => {
                    this.loading = false;
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
                        toastr.error('Ocurrio un error mostrando los usuarios', error.response.status);
                    }
                });

        },
        onCancel: function() {
            console.log('User cancelled the loader.')
        },
        getUsersIndexOrder: function(field, pageNo) {
            this.isLoading = true;
            this.orderBy.field = field;

            if (this.orderBy.order == 'asc') {
                this.orderBy.order = 'desc';
            } else if (this.orderBy.order == 'desc') {
                this.orderBy.order = 'asc';
            }
            var url = route('user.datatable');
            url = url.concat("?page=");
            url = url.concat(pageNo);

            axios.get(url, {
                    params: {
                        field: this.orderBy.field,
                        status: this.orderBy.status,
                        order: this.orderBy.order,
                        count: this.orderBy.count,
                        search: this.orderBy.search
                    }
                })
                .then(response => {
                    this.users = response.data.result.users.data;
                    this.datatable.nextPageUrl = response.data.result.users.next_page_url;
                    this.datatable.prevPageUrl = response.data.result.users.prev_page_url;
                    this.datatable.totalPages = response.data.result.users.last_page;
                    this.datatable.currentPage = response.data.result.users.current_page;
                    this.isLoading = false;
                })
                .catch(error => {
                    this.loading = false;
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
                        toastr.error('Ocurrio un error mostrando los usuarios', error.response.status);
                    }
                });
        },
        getUsersPageNextPrev: function(PageUrl) {
            this.isLoading = true;
            if (PageUrl) {
                return axios.get(PageUrl, {
                        params: {
                            field: this.orderBy.field,
                            status: this.orderBy.status,
                            order: this.orderBy.order,
                            count: this.orderBy.count,
                            search: this.orderBy.search
                        }
                    })
                    .then(response => {
                        this.users = response.data.result.users.data;
                        this.datatable.nextPageUrl = response.data.result.users.next_page_url;
                        this.datatable.prevPageUrl = response.data.result.users.prev_page_url;
                        this.datatable.totalPages = response.data.result.users.last_page;
                        this.datatable.currentPage = response.data.result.users.current_page;
                        this.isLoading = false;
                    })
                    .catch(error => {
                        this.loading = false;
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
                            toastr.error('Ocurrio un error mostrando los usuarios', error.response.status);
                        }
                    });
            } else {
                this.isLoading = false;
            }
        },
        cssClassOrder: function() {
            if (this.orderBy.order == 'asc') {
                return 'glyphicon glyphicon-chevron-up';
            } else {
                return 'glyphicon glyphicon-chevron-down';
            }
        },
        cssClassPage: function(pageNo) {
            if (pageNo == this.datatable.currentPage) {
                return 'active';
            }
        },
        cssClassNextPrev: function(PageUrl) {
            if (PageUrl == null) {
                return 'disabled';
            }
        },
    }
}
</script>
