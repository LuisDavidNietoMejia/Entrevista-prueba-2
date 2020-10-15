<template>
<div class="panel">
    <hr>
    <div class="panel-heading text-center">

        <documentStore></documentStore>
        <documentEdit></documentEdit>
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
                <a class="btn btn-primary" v-on:click="showStore()">Crear Nuevo Documento</a>
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

                        <th v-on:click="getDocumentsIndexOrder('documents.name',datatable.currentPage)">
                            Usuario Relaciòn<i v-if="orderBy.field == 'documents.name'" :class="cssClassOrder()"></i>
                        </th>

                        <th v-on:click="getDocumentsIndexOrder('documents.email',datatable.currentPage)">
                            Email Relaciòn<i v-if="orderBy.field == 'documents.email'" :class="cssClassOrder()"></i>
                        </th>

                        <th v-on:click="getDocumentsIndexOrder('documents.email',datatable.currentPage)">
                            Contenido Documento<i v-if="orderBy.field == 'documents.email'" :class="cssClassOrder()"></i>
                        </th>

                        <th v-on:click="getDocumentsIndexOrder('documents.email',datatable.currentPage)">
                            Creado por<i v-if="orderBy.field == 'documents.email'" :class="cssClassOrder()"></i>
                        </th>

                        <th v-on:click="getDocumentsIndexOrder('documents.email',datatable.currentPage)">
                            Actualizado Por<i v-if="orderBy.field == 'documents.email'" :class="cssClassOrder()"></i>
                        </th>

                        <th>Editar Documento</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="document in documents" v-bind:key="document.id">
                        <td>{{ document.user_name }}</td>
                        <td>{{ document.user_email }}</td>
                        <td>{{ document.content }}</td>
                        <td>{{ document.createdBy }}</td>
                        <td>{{ document.modifiedBy }}</td>
                        <td>
                            <a v-on:click="showUpdate(document)">
                                <i class="fal fa-user-edit"></i>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="text-center">
                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        <li :class="[cssClassNextPrev(datatable.prevPageUrl),' page-item']">
                            <a @click="getDocumentsPageNextPrev(datatable.prevPageUrl)" class="page-link">Previous</a>
                        </li>
                        <li :class="cssClassPage(pageNo)" :key="pageNo" v-for="pageNo in datatable.totalPages">
                            <a @click="getDocumentsIndex(pageNo)" class="page-link" href="#">{{ pageNo }}</a>
                        </li>
                        <li :class="[cssClassNextPrev(datatable.nextPageUrl),' page-item']">
                            <a @click="getDocumentsPageNextPrev(datatable.nextPageUrl)" class="page-link">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
</template>

<script>
import documentStore from './document-store';
import documentEdit from './document-edit';
import EventBus from '../event-bus';
import Echo from 'laravel-echo'
window.Pusher = require('pusher-js')

var moment = require('moment');

export default {
      props: {
      user_id: {
        type: Number,
        required: true
      },
    },
    mounted() {
        console.log('Component mounted document index.')
        this.getDocumentsIndex(this.datatable.currentPage);
        EventBus.$on('document-modal-store', dataArray => {
            this.getDocumentsIndex(this.datatable.currentPage);
        })
        EventBus.$on('document-modal-update', dataArray => {
            this.getDocumentsIndex(this.datatable.currentPage);
        })
        this.launchWebsockets();
    },
    created: function() {
        console.log('Component created document index.')
    },
    data: function() {
        return {
            isLoading: false,
            documents: [],
            datatable: {
                nextPageUrl: '',
                prevPageUrl: '',
                totalPages: '',
                currentPage: ''
            },
            fillDocument: {
                id: '',
                name: '',
                email: ''
            },
            moment: moment,
            orderBy: {
                field: 'documents.id',
                status: 'activo',
                order: 'asc',
                count: '5',
                search: ''
            }
        }
    },
    components: {
        documentStore,
        documentEdit
    },
    methods: {
        launchWebsockets: function() {
            window.Echo.private('document.' + this.user_id).listen('UserDocumentEvent', (e) => {
                toastr.success(e.text, 200);
                this.getDocumentsIndex();
            });
        },
        onChangeSearch(event, pageNo) {
            this.isLoading = true;
            if (event.target.name == 'countSearch') {
                this.orderBy.search = "";
            }
            var url = route('document.datatable');
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
                    this.documents = response.data.result.documents.data;
                    this.datatable.nextPageUrl = response.data.result.documents.next_page_url;
                    this.datatable.prevPageUrl = response.data.result.documents.prev_page_url;
                    this.datatable.totalPages = response.data.result.documents.last_page;
                    this.datatable.currentPage = response.data.result.documents.current_page;
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
            this.$modal.show('document-modal-store');
        },
        showUpdate: function(document) {
            this.$modal.show('document-modal-update', {
                modelDocument: document
            })
        },
        hide: function() {
            this.$modal.hide('document-modal-store')
            this.fillDocument = []
        },
        getDocumentsIndex: function(pageNo) {
            this.isLoading = true;

            var url = route('document.datatable');
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
                    this.documents = response.data.result.documents.data;
                    this.datatable.nextPageUrl = response.data.result.documents.next_page_url;
                    this.datatable.prevPageUrl = response.data.result.documents.prev_page_url;
                    this.datatable.totalPages = response.data.result.documents.last_page;
                    this.datatable.currentPage = response.data.result.documents.current_page;
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
        getDocumentsIndexOrder: function(field, pageNo) {
            this.isLoading = true;
            this.orderBy.field = field;

            if (this.orderBy.order == 'asc') {
                this.orderBy.order = 'desc';
            } else if (this.orderBy.order == 'desc') {
                this.orderBy.order = 'asc';
            }
            var url = route('document.datatable');
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
                    this.documents = response.data.result.documents.data;
                    this.datatable.nextPageUrl = response.data.result.documents.next_page_url;
                    this.datatable.prevPageUrl = response.data.result.documents.prev_page_url;
                    this.datatable.totalPages = response.data.result.documents.last_page;
                    this.datatable.currentPage = response.data.result.documents.current_page;
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
        getDocumentsPageNextPrev: function(PageUrl) {
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
                        this.documents = response.data.result.documents.data;
                        this.datatable.nextPageUrl = response.data.result.documents.next_page_url;
                        this.datatable.prevPageUrl = response.data.result.documents.prev_page_url;
                        this.datatable.totalPages = response.data.result.documents.last_page;
                        this.datatable.currentPage = response.data.result.documents.current_page;
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
                return 'active ';
            }
        },
        cssClassNextPrev: function(PageUrl) {
            if (PageUrl == null) {
                return 'disabled ';
            }
        },
    }
}
</script>
