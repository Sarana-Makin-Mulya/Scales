<template>
    <div>
        <div>
            <div class="search-and-filter row mb-2 d-flex align-items-center">
                <!-- Export -->
                <div class="col-12 col-md-6">
                </div>

                <!-- Search -->
                <div class="col-12 col-md-6 text-md-right">
                    <input
                        type="text"
                        name="search"
                        v-model="queryParams.keyword"
                        class="form-control form-control-sm search-form"
                        placeholder="Cari Penadah Barang Sampah"
                        @change="search()"
                    >
                    <button
                            type="button"
                            class="btn btn-primary dropdown-toggle dropdown-icon btn-sm"
                            data-toggle="dropdown"
                            aria-expanded="false"
                        >
                            <i class="fas fa-file-export"></i> Export
                            <div class="dropdown-menu" role="menu" x-placement="bottom-start">
                                <a class="dropdown-item" href="#">
                                    <download-excel
                                        title = "Daftar Penadah Barang Sampah"
                                        :fetch   = "fetchData"
                                        :fields = "json_fields"
                                        worksheet = "My Worksheet"
                                        :name    = "fileName">
                                    Excel
                                    </download-excel>
                                </a>
                            </div>
                        </button>
                        <a
                            href="#"
                            @click.prevent="addForm()"
                            class="btn btn-primary btn-sm"
                            data-toggle="tooltip"
                            title="Tambah Data Penadah Barang Samah"
                        >
                            <i class="fas fa-plus-square"></i> Tambah
                        </a>
                </div>


            </div>
        </div>
        <div class="card">
            <!-- Card Body -->
            <div class="card-body py-0">
                <b-table
                    :items="fetchData"
                    :fields="fields"
                    :tbody-tr-class="rowClass"
                    ref="table"
                    :per-page="queryParams.per_page"
                    :current-page="queryParams.page"
                    :busy.sync="isBusy"
                    :sort-by.sync="queryParams.order_by"
                    :sort-desc.sync="queryParams.sort_desc"
                    show-empty
                    table-class="custom-table"
                    hover
                    striped
                    responsive
                >
                    <!-- Index -->
                    <template v-slot:cell(index)="data">
                        {{ (queryParams.page !== 1) ? (data.index + 1) + (queryParams.per_page) * (queryParams.page - 1) : data.index + 1 }}
                    </template>

                    <!-- Action -->
                    <template v-slot:cell(action)="data">

                        <!-- Edit Button -->
                        <a
                            href="#"
                            @click.prevent="editForm(data.item)"
                            class="action-button text-secondary"
                            data-toggle="tooltip"
                            title="Ubah data"
                        >
                            <i class="fas fa-edit"></i>
                        </a>

                        <!-- Delete Button -->
                        <a
                            href="#"
                            class="action-button text-secondary"
                            data-toggle="tooltip"
                            title="Hapus data   "
                            @click.prevent="destroy(data.item)"
                        >
                            <i class="far fa-trash-alt"></i>
                        </a>

                        <span v-if="data.item.id==new_data">
                            {{ new_status }}
                        </span>

                    </template>

                    <!-- Empty Message -->
                    <template v-slot:empty="scope">
                        <h4 class="m-0 p-2 text-secondary">Tidak ada data untuk ditampilkan.</h4>
                    </template>
                </b-table>
            </div>

            <!-- Footer -->
            <div class="card-footer">
                <div class="row">
                     <!-- Perpage -->
                    <div class="col-12 col-md-4">
                        <b-form-select
                            v-model="queryParams.per_page"
                            :options="pageOptions"
                            size="sm"
                            class="d-inline select-per-page"
                        ></b-form-select>
                        <span class="text-black">Data Per Halaman</span>
                    </div>

                    <!-- Text record -->
                    <div class="col-12 col-md-4 text-muted text-center">
                        <span>Menampilkan {{ meta.from }} sampai {{ meta.to }} dari total {{ meta.total }} data.</span>
                    </div>

                    <!-- Pagination -->
                    <div class="col-12 col-md-4">
                        <b-pagination
                            v-model="queryParams.page"
                            :total-rows="totalRows"
                            :per-page="queryParams.per_page"
                            align="right"
                            size="sm"
                            class="mb-0"
                        ></b-pagination>
                    </div>
                </div>
            </div>
        </div>

        <!-- Create -->
         <b-modal ref="bv-modal-add-form" :title="modalTitle" footer-class="p-2" size="md" no-close-on-backdrop hide-header-close>
            <div class="d-block text-center px-3 py-2">
                <validation-observer ref="observer" v-slot="{ invalid }" tag="form" @submit.prevent="submitForm()">
                    <b-form @submit.prevent="submitForm">
                        <div class="row bg-light rmb-20">
                            <div class="col-md-12">
                                <!-- Name -->
                                <b-form-group id="group-name" label="Nama:" label-for="name" class="text-left">
                                    <validation-provider mode="passive" name="Nama" :rules="{required: true, unique: { url: urlCheckNameExist, id: form.id }}" v-slot="{ errors }">
                                        <b-form-input
                                            id="name"
                                            v-model="form.name"
                                            type="text"
                                            required
                                            placeholder="Masukan Nama"
                                            autocomplete="off"
                                            class="form-control form-control-sm"
                                        ></b-form-input>
                                        <span class="form-error-message" v-if="errors[0]">{{ errors[0] }}</span>
                                    </validation-provider>
                                </b-form-group>
                            </div>

                            <div class="col-md-12">
                                <!-- Description -->
                                <b-form-group id="group-description" label="Deskripsi:" label-for="description" class="text-left">
                                    <validation-provider mode="passive" name="Deskripsi" v-slot="{ errors }">
                                        <b-form-textarea
                                            id="description"
                                            class="form-control"
                                            placeholder="Deskripsikan Mesin"
                                            v-model="form.description"
                                            rows="4"
                                            no-resize
                                        >
                                        </b-form-textarea>

                                        <span class="form-error-message" v-if="errors[0]">{{ errors[0] }}</span>
                                    </validation-provider>
                                </b-form-group>
                            </div>
                        </div>

                        <button type="submit" class="d-none">Kirim</button>
                    </b-form>
                </validation-observer>
            </div>

            <template v-slot:modal-footer>
                <div class="w-100 m-0 text-center">
                    <button class="btn btn-md btn-primary" @click="submitForm()">
                        Kirim
                    </button>
                    <button class="btn btn-md btn-outline-danger border-0" @click="hideModal('bv-modal-add-form','form')">
                        Batal
                    </button>
                </div>
            </template>
        </b-modal>

    </div>
</template>

<script>
import moment from 'moment'
import vSelect from 'vue-select'
import { Notification, MessageBox } from 'element-ui'
import { BModal, BTable, BInputGroup, BPagination, BFormRadioGroup, BForm, BFormGroup, BFormInput, BFormCheckbox, BFormTextarea, BFormSelect } from 'bootstrap-vue'
import { extend } from 'vee-validate'
import { required } from 'vee-validate/dist/rules';
// Validation
extend('required', required);
extend('unique', {
    params: ['url', 'id'],
    async validate(value, { url, id }) {
        let status

        await axios.get(url, { params: { value: value, id: id } })
                .then(response => {
                    status = response.data.status
                })
                .catch(error => {
                    status = error.response.data.status
                })

        return status
    },
    message: '{_field_} sudah terdaftar'
});

export default {
    components: {
        BModal,
        BTable,
        BInputGroup,
        BPagination,
        BFormSelect,
        BForm,
        BFormGroup,
        BFormInput,
        BFormTextarea,
        BFormCheckbox,
        BFormRadioGroup,
        Notification,
        MessageBox,
        vSelect,
    },
    props: {
        urlData: {
            required: true,
            type: String,
        },
        urlStore: {
            required: true,
            type: String,
        },
        urlCheckNameExist: {
            required: true,
            type: String,
        },
    },
    data() {
        return {
            items: [],
            new_data: null,
            new_status : null,
            form: {
                id: null,
                name: null,
                description: null,
                url: this.urlStore,
                method: 'POST'
            },
            queryParams: {
                per_page: 15,
                keyword: null,
                page: 1,
            },
            totalRows: 0,
            isBusy: false,
            fields: [
                { key: 'index', label: '#', thStyle: 'text-align: center; width: 35px;', tdClass: 'custom-cell text-center' },
                { key: 'name', label: 'Nama', thStyle: 'text-align: left;', tdClass: 'custom-cell text-left', sortable: true},
                { key: 'description', label: 'Deskripsi', thStyle: 'text-align: left;', tdClass: 'custom-cell text-left', sortable: true},
                { key: 'action', label: 'Aksi', thStyle: 'text-align: center; min-width: 60px;', tdClass: 'custom-cell text-center' }
            ],
            meta: [],
            modalTitle: undefined,
            modalTitleNewAdd: undefined,
            pageOptions: [
                { text: 15, value: 15, disabled: false },
                { text: 25, value: 25, disabled: false },
                { text: 50, value: 50, disabled: false },
                { text: 100, value: 100, disabled: false },
            ],
            isHidden: false,
            json_fields: {
                'Nama': 'name',
                'Deskripsi': 'description',
            },
            json_meta: [
                [
                    {
                        'key': 'charset',
                        'value': 'utf-8'
                    }
                ]
            ],
            fileName: 'master kategori-penimbangan' + moment().format("DD-MM-YYYY HH:MM")+ '.xls',
        }
    },
    methods: {
        rowClass(item, type) {
            if (!item || type !== 'row') return
            if (item.id == this.new_data)  return 'table-warning'
        },
        fetchData(ctx) {
            let promise = axios.get(this.urlData, { params: this.queryParams })
            return promise.then((response) => {
                    this.items = response.data
                    const items = response.data.data
                    this.meta = response.data.meta
                    this.totalRows = response.data.meta.total

                    return (items)
                })
                .catch((error) => {
                    return []
                })
        },
        addForm(){
            this.modalTitle = 'Tambah Kategori Penimbangan'
            this.$refs['bv-modal-add-form'].show()
            this.form.id = null
            this.form.name = null
            this.form.description = null
            this.form.url =  this.urlStore
            this.form.method =  'POST'
        },
        editForm(item){
            this.modalTitle = 'Ubah Kategori Penimbangan'
            this.$refs['bv-modal-add-form'].show()
            this.form.id = item.id
            this.form.name = item.name
            this.form.description = item.description
            this.form.url =  item.url_edit
            this.form.method =  'PUT'
        },
        destroy(item) {
            this.$confirm('Apakah Anda yakin ingin menghapus "' + item.name + '" dari daftar kategori penimbangan ?', 'Peringatan', {
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal',
                type: 'error'
            })
                .then(() => {
                    axios.delete(item.url_delete)
                        .then(response => {
                            this.$refs.table.refresh();
                            this.$message({
                                type: 'success',
                                message: response.data.message
                            });
                        })
                        .catch(error => {
                            this.$message({
                                type: 'error',
                                message: error.response.status + ': ' +error.response.data.message
                            });
                        })
                })
                .catch(() => {
                    this.$message({
                        type: 'info',
                        message: 'Penghapusan dibatalkan.'
                    })
                })
        },
        hideModal(modal, type) {
            if(type=="process"){
                this.$refs[modal].hide()
            }else{
                this.$confirm('Apakah anda yakin akan keluar dari form ?', {
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Batal',
                    type: 'warning'
                })
                .then(() => {
                    this.$refs[modal].hide()
                })
            }
        },
        search() {
            this.fetchData()
            this.$refs.table.refresh()
        },
        async submitForm() {
            const isValid = await this.$refs.observer.validate();
            if (!isValid) {
                return
            }

            axios({
                method: this.form.method,
                url: this.form.url,
                data: {
                    name: this.form.name,
                    description: this.form.description,
                }
            })
                .then(response => {
                    this.hideModal('bv-modal-add-form', 'process')
                    this.$notify({
                        title: 'Sukses',
                        message: response.data.message,
                        type: 'success',
                    })
                    this.$refs.table.refresh()
                    // Update/Create Status
                    this.new_data = response.data.id
                    this.new_status = response.data.act
                    if(response.data.changed) this.queryParams.page = 1
                    var data = this
                    setTimeout(function(){
                        data.new_data = null
                        data.new_status = null
                    },5000);
                })
                .catch(error => {
                    const errorResponse = error.response

                    if (errorResponse.status !== 422) {
                        this.hideModal('bv-modal-add-form', 'process')
                        this.$message({
                            showClose: true,
                            message: 'Oops, Sepertinya error nih, mohon dicoba kembali ya!',
                            type: 'error'
                        });
                    }
                })
        },
    }
}
</script>
