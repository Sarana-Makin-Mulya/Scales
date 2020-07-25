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
                        class="form-control search-form"
                        placeholder="Cari Data Penimbangan"
                        @change="search()"
                    >
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

                    <template v-slot:cell(netto_weight)="data">
                        {{ formatNumber(data.value) }} Kg
                    </template>

                    <template v-slot:cell(first_datetime)="data">
                        {{ data.value | moment_datetime }}
                    </template>

                     <template v-slot:cell(stage)="data">
                        <span v-if="data.value==1" class="badge badge-pill badge-warning">Penimbangan Pertama</span>
                        <span v-if="data.value==2" class="badge badge-pill badge-success">Penimbangan Kedua</span>
                    </template>

                    <!-- Action -->
                    <template v-slot:cell(action)="data">

                        <!-- Preview Button -->
                        <a
                            href="#"
                            @click.prevent="preview(data.item)"
                            class="action-button text-secondary"
                            data-toggle="tooltip"
                            title="Detail"
                        >
                            <i class="fas fa-search"></i>
                        </a>

                        <!-- Edit Button  -->
                        <!-- <a
                            href="#"
                            @click.prevent="editForm(data.item)"
                            class="action-button text-secondary"
                            data-toggle="tooltip"
                            title="Ubah data"
                        >
                            <i class="fas fa-edit"></i>
                        </a> -->

                        <!-- Delete Button  -->
                        <!-- <a
                            href="#"
                            class="action-button text-secondary"
                            data-toggle="tooltip"
                            title="Hapus data   "
                            @click.prevent="destroy(data.item)"

                        >
                            <i class="far fa-trash-alt"></i>
                        </a> -->

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

        <!-- Modal Preivew-->
        <b-modal ref="bv-modal-preview" :title="modalTitle" footer-class="p-2" size="xl" ok-only>
            <div>
               <weighing-detail
                :id='previewId'
               >
               </weighing-detail>
            </div>
        </b-modal>

        <!-- Create Weighing -->
        <b-modal ref="bv-modal-weighing-form" :title="modalTitle" footer-class="p-2" size="xl" no-close-on-backdrop hide-header-close>
            <div class="d-block text-center px-3 py-2">
                <validation-observer ref="observer" v-slot="{ invalid }" tag="form" @submit.prevent="submitForm()">
                    <b-form @submit.prevent="submitForm">
                        <div class="row bg-light rmb-20">
                            <div class="col-md-4 p-2">
                                <!-- Category -->
                                <b-form-group id="group-category" label="Kategori :" label-for="category" class="text-left">
                                    <div @click="AddNewWeighingCategoryOptions()"
                                        style="color:#38c172;font-weight:bold;cursor:pointer;margin-top:-20px;margin-left:61px;width:15px"
                                        id='tooltip-add-category'
                                    >
                                        <i class="far fa-plus-square"></i>
                                        <b-tooltip placement="top" target="tooltip-add-category" title="Tambah Master Kategori Penimbangan"></b-tooltip>
                                    </div>
                                    <validation-provider mode="passive" name="Kategori" :rules="{required: true}" v-slot="{ errors }">
                                        <v-select
                                            label="name"
                                            v-model="form.weighing_category_id"
                                            :options="weighingCategoryOptions"
                                            :reduce="name => name.id"
                                        >
                                            <template slot="no-options">
                                                <div @click="AddNewWeighingCategoryOptions()" style="color:#38c172;font-weight:bold;cursor:pointer">
                                                    <i class="far fa-plus-square"></i> Tambah Master Kategori Penimbangan
                                                </div>
                                            </template>
                                        </v-select>

                                        <span class="form-error-message" v-if="errors[0]">{{ errors[0] }}</span>
                                    </validation-provider>
                                </b-form-group>
                            </div>
                            <div class="col-md-4 p-2">
                                <!-- Receiper -->
                                <b-form-group id="group-receiper" label="Penerima :" label-for="receiper" class="text-left">
                                    <validation-provider mode="passive" name="Penerima" :rules="{required: true}" v-slot="{ errors }">
                                        <b-form-input
                                            id="receiper"
                                            v-model="form.receiper"
                                            type="text"
                                            placeholder="Masukan penerima"
                                            autocomplete="off"
                                            class="form-control form-control-sm text-left"
                                        ></b-form-input>
                                        <span class="form-error-message" v-if="errors[0]">{{ errors[0] }}</span>
                                    </validation-provider>
                                </b-form-group>
                            </div>
                            <div class="col-md-4 p-2">
                                &nbsp;
                            </div>
                        </div>

                        <div class="row bg-light rmb-20">
                            <div class="col-md-4 p-2">
                                <!--  Supplier Name -->
                                <b-form-group id="group-supplier-name" label="Nama Supplier :" label-for="supplier_name" class="text-left">
                                    <validation-provider mode="passive" name="Nomor nama supplier" :rules="{required: true}" v-slot="{ errors }">
                                        <b-form-input
                                            id="supplier_name"
                                            v-model="form.supplier_name"
                                            type="text"
                                            placeholder="Masukan Nama Supplier"
                                            autocomplete="off"
                                            class="form-control form-control-sm text-left"
                                        ></b-form-input>
                                        <span class="form-error-message" v-if="errors[0]">{{ errors[0] }}</span>
                                    </validation-provider>
                                </b-form-group>
                            </div>
                            <div class="col-md-4 p-2">
                                <!-- DO Code -->
                                <b-form-group id="group-do-code" label="Nomor Surat Jalan :" label-for="do_code" class="text-left">
                                    <validation-provider mode="passive" name="Nomor surat jalan" :rules="{required: true}" v-slot="{ errors }">
                                        <b-form-input
                                            id="do_code"
                                            v-model="form.do_code"
                                            type="text"
                                            placeholder="Masukan Nomor Surat Jalan"
                                            autocomplete="off"
                                            class="form-control form-control-sm text-left"
                                        ></b-form-input>
                                        <span class="form-error-message" v-if="errors[0]">{{ errors[0] }}</span>
                                    </validation-provider>
                                </b-form-group>
                            </div>
                            <div class="col-md-4 p-2">
                                <!--  Driver Name -->
                                <b-form-group id="group-driver-name" label="Nama Supir :" label-for="driver_name" class="text-left">
                                    <validation-provider mode="passive" name="Nama Supir" :rules="{required: true}" v-slot="{ errors }">
                                        <b-form-input
                                            id="driver_name"
                                            v-model="form.driver_name"
                                            type="text"
                                            placeholder="Masukan nama supir"
                                            autocomplete="off"
                                            class="form-control form-control-sm text-left"
                                        ></b-form-input>
                                        <span class="form-error-message" v-if="errors[0]">{{ errors[0] }}</span>
                                    </validation-provider>
                                </b-form-group>
                            </div>
                        </div>

                        <!--  B : WEIGHING -->
                        <div class="row bg-light rmb-20">
                            <div class="col-md-12 p-2">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th width="8%">Penimbangan</th>
                                            <th width="15%">Nomor Polisi</th>
                                            <th width="15%">Tanggal</th>
                                            <th width="32%">Operator</th>
                                            <th width="30%">Berat (Kg)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-left">Pertama</td>
                                            <td class="text-left">
                                                <validation-provider mode="passive" name="No polisi awal" :rules="{required: true}" v-slot="{ errors }">
                                                    <b-form-input
                                                        id="first_number_plate"
                                                        v-model="form.first_number_plate"
                                                        type="text"
                                                        placeholder="No Polisi"
                                                        autocomplete="off"
                                                        class="form-control form-control-sm text-left"
                                                    ></b-form-input>
                                                    <span class="form-error-message" v-if="errors[0]">{{ errors[0] }}</span>
                                                </validation-provider>
                                            </td>
                                            <td class="text-left">
                                                <!-- First Datetime -->
                                                <validation-provider mode="passive" name="Tanggal Awal " :rules="{required: true}" v-slot="{ errors }">
                                                    <datepicker
                                                        id="first_datetime"
                                                        v-model="form.first_datetime"
                                                        type="datetime"
                                                        lang="en"
                                                        valueType="YYYY-MM-DD HH:mm:ss"
                                                        format="DD MMMM YYYY HH:mm"
                                                        :disabled-date="disabledDateAfterToday"
                                                        :editable="false"
                                                        placeholder="Masukan tanggal"
                                                        input-class="form-control form-control-sm bg-white"
                                                        style="background-color:#FF0"
                                                        confirm
                                                    ></datepicker>
                                                    <span class="form-error-message" v-if="errors[0]">{{ errors[0] }}</span>
                                                </validation-provider>
                                            </td>
                                            <td>
                                                <validation-provider mode="passive" name="Operator awal" :rules="{required: true}" v-slot="{ errors }">
                                                    <v-select
                                                        label="detail"
                                                        v-model="form.first_operator_by"
                                                        :options="employeeOptions"
                                                        :reduce="detail => detail.nik"
                                                    >
                                                    </v-select>
                                                    <span class="form-error-message" v-if="errors[0]">{{ errors[0] }}</span>
                                                </validation-provider>
                                            </td>
                                            <td class="text-center">
                                                <div class="row" style="margin-left:-15px">
                                                    <div class="col-md-4 p-0 pr-1 text-right">
                                                         <div class="btn-group btn-group-sm-left">
                                                            <button type="button" class="btn btn-weighing" @click="setCount('first', '-', 100)">-100</button>
                                                            <button type="button" class="btn btn-weighing" @click="setCount('first', '-', 10)">-10</button>
                                                            <button type="button" class="btn btn-weighing" @click="setCount('first', '-', 1)">-1</button>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 p-0">
                                                    <validation-provider mode="passive" name="Berat Awal" :rules="{required: true}" v-slot="{ errors }">
                                                        <b-form-input
                                                            id="first_weight"
                                                            v-model="form.first_weight"
                                                            type="number"
                                                            placeholder="Masukan berat"
                                                            autocomplete="off"
                                                            @blur="checkQuantity(form.first_weight, 'first')"
                                                            class="form-control form-control-sm-weighing text-center"
                                                        ></b-form-input>
                                                        <span class="form-error-message" v-if="errors[0]">{{ errors[0] }}</span>
                                                    </validation-provider>
                                                    </div>
                                                    <div class="col-md-4 p-0 pl-1 text-left">
                                                        <div class="btn-group btn-group-sm-right">
                                                            <button type="button" class="btn btn-weighing" @click="setCount('first', '+', 1)">+1</button>
                                                            <button type="button" class="btn btn-weighing" @click="setCount('first', '+', 10)">+10</button>
                                                            <button type="button" class="btn btn-weighing" @click="setCount('first', '+', 100)">+100</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-left v-align-middle">Kedua</td>
                                            <td class="text-left">
                                                <validation-provider mode="passive" name="No polisi akhir" :rules="{required: true}" v-slot="{ errors }">
                                                    <b-form-input
                                                        id="second_number_plate"
                                                        v-model="form.second_number_plate"
                                                        type="text"
                                                        placeholder="No Polisi"
                                                        autocomplete="off"
                                                        class="form-control form-control-sm text-left"
                                                    ></b-form-input>
                                                    <span class="form-error-message" v-if="errors[0]">{{ errors[0] }}</span>
                                                </validation-provider>
                                            </td>
                                            <td class="text-left">
                                                <validation-provider mode="passive" name="Tanggal Akhir " :rules="{required: true}" v-slot="{ errors }">
                                                    <datepicker
                                                        id="second_datetime"
                                                        v-model="form.second_datetime"
                                                        type="datetime"
                                                        lang="en"
                                                        valueType="YYYY-MM-DD HH:mm:ss"
                                                        format="DD MMMM YYYY HH:mm"
                                                        :disabled-date="disabledDateAfterToday"
                                                        :editable="false"
                                                        placeholder="Masukan tanggal"
                                                        input-class="form-control form-control-sm-weighing bg-white"
                                                        style="background-color:#FF0"
                                                        confirm
                                                    ></datepicker>
                                                    <span class="form-error-message" v-if="errors[0]">{{ errors[0] }}</span>
                                                </validation-provider>
                                            </td>
                                            <td>
                                                <validation-provider mode="passive" name="Operator akhir" :rules="{required: true}" v-slot="{ errors }">
                                                    <v-select
                                                        label="detail"
                                                        v-model="form.second_operator_by"
                                                        :options="employeeOptions"
                                                        :reduce="detail => detail.nik"
                                                    >
                                                    </v-select>
                                                    <span class="form-error-message" v-if="errors[0]">{{ errors[0] }}</span>
                                                </validation-provider>
                                            </td>
                                            <td class="text-center">
                                                <div class="row" style="margin-left:-15px">
                                                    <div class="col-md-4 p-0 pr-1 text-right">
                                                         <div class="btn-group btn-group-sm-left">
                                                            <button type="button" class="btn btn-weighing" @click="setCount('second', '-', 100)">-100</button>
                                                            <button type="button" class="btn btn-weighing" @click="setCount('second', '-', 10)">-10</button>
                                                            <button type="button" class="btn btn-weighing" @click="setCount('second', '-', 1)">-1</button>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 p-0">
                                                    <validation-provider mode="passive" name="Berat Akhir" :rules="{required: true}" v-slot="{ errors }">
                                                        <b-form-input
                                                            id="second_weight"
                                                            v-model="form.second_weight"
                                                            type="number"
                                                            placeholder="Masukan berat"
                                                            autocomplete="off"
                                                            @blur="checkQuantity(form.second_weight, 'second')"
                                                            class="form-control form-control-sm-weighing text-center"
                                                        ></b-form-input>
                                                        <span class="form-error-message" v-if="errors[0]">{{ errors[0] }}</span>
                                                    </validation-provider>
                                                    </div>
                                                    <div class="col-md-4 p-0 pl-1 text-left">
                                                        <div class="btn-group btn-group-sm-right m-0 ">
                                                            <button type="button" class="btn btn-weighing" @click="setCount('second', '+', 1)">+1</button>
                                                            <button type="button" class="btn btn-weighing" @click="setCount('second', '+', 10)">+10</button>
                                                            <button type="button" class="btn btn-weighing" @click="setCount('second', '+', 100)">+100</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" class="text-bold text-right">
                                                Berat Toleransi
                                            </td>
                                            <td>
                                                 <div class="row" style="margin-left:-15px">
                                                    <div class="col-md-4 p-0 pr-1 text-right">
                                                        <div class="btn-group btn-group-sm-left">
                                                            <button type="button" class="btn btn-weighing" @click="setCount('tolerance', '-', 100)">-100</button>
                                                            <button type="button" class="btn btn-weighing" @click="setCount('tolerance', '-', 10)">-10</button>
                                                            <button type="button" class="btn btn-weighing" @click="setCount('tolerance', '-', 1)">-1</button>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 p-0">
                                                    <validation-provider mode="passive" name="Berat Toleransi" :rules="{required: true}" v-slot="{ errors }">
                                                        <b-form-input
                                                            id="tolerance_weight"
                                                            v-model="form.tolerance_weight"
                                                            type="number"
                                                            placeholder="Masukan berat"
                                                            autocomplete="off"
                                                            @blur="checkQuantity(form.tolerance_weight, 'tolerance')"
                                                            class="form-control form-control-sm-weighing text-center"
                                                        ></b-form-input>
                                                        <span class="form-error-message" v-if="errors[0]">{{ errors[0] }}</span>
                                                    </validation-provider>
                                                    </div>
                                                    <div class="col-md-4 p-0 pl-1 text-left">
                                                        <div class="btn-group btn-group-sm-right m-0">
                                                            <button type="button" class="btn btn-weighing" @click="setCount('tolerance', '+', 1)">+1</button>
                                                            <button type="button" class="btn btn-weighing" @click="setCount('tolerance', '+', 10)">+10</button>
                                                            <button type="button" class="btn btn-weighing" @click="setCount('tolerance', '+', 100)">+100</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!--  E : WEIGHING -->

                        <!--  B : TOLERANCE -->
                        <div class="row bg-light rmb-20" v-if="form.tolerance_weight>0">
                            <div class="col-md-12 p-2">
                                <!-- Tolerance Reason -->
                                <b-form-group id="group-tolerance-reason" label="Alasan Toleransi :" label-for="tolerance_reason" class="text-left">
                                    <validation-provider mode="passive" name="Alasan Toleransi" :rules="{required: true}" v-slot="{ errors }">
                                        <b-form-textarea
                                            id="tolerance_reason"
                                            class="form-control"
                                            placeholder="Alasan Toleransi"
                                            v-model="form.tolerance_reason"
                                            rows="4"
                                            no-resize
                                        >
                                        </b-form-textarea>
                                        <span class="form-error-message" v-if="errors[0]">{{ errors[0] }}</span>
                                    </validation-provider>
                                </b-form-group>
                            </div>
                        </div>
                        <!--  E : TOLERANCE -->

                        <!--  B : FILE -->
                        <div class="row bg-light rmb-20">
                            <div class="col-md-4 p-2">
                                <!-- Upload file -->
                                <b-form-group id="group-file" label="Upload File :" label-for="file" class="text-left">
                                    <validation-provider mode="passive" name="Upload file" :rules="{required: (form.file_prev!=null) ? false : true}" v-slot="{ errors }">
                                        <b-form-file
                                            v-model="form.file"
                                            placeholder="Pilih file"
                                            drop-placeholder="Drop file here..."
                                            size="sm"
                                            accept=".jpg, .png, .gif, .jpeg"
                                        ></b-form-file>
                                        <div v-if="form.file_prev!=null && form.file==null">
                                            <div class="mt-3">File :
                                                <!-- :state="Boolean(form.approvals_file)" -->
                                                <a href="#"
                                                    @click.prevent="downloadFile(form.item)"
                                                    data-toggle="tooltip"
                                                    title="Download file">
                                                    {{ form.file_prev }}
                                                </a>
                                            </div>
                                            <small id="passwordHelpBlock" class="form-text text-muted">
                                                Silahkan klik file untuk donwload, dan pilih file apabila ingin mengubah file penimbangan
                                            </small>
                                        </div>
                                        <div v-else>
                                            <div v-if="form.file" class="mt-3">File: {{ form.file ? form.file.name : '' }}</div>
                                        </div>
                                        <span class="form-error-message" v-if="errors[0]">{{ errors[0] }}</span>
                                    </validation-provider>
                                </b-form-group>
                            </div>
                        </div>
                        <!--  E : FILE -->

                        <button type="submit" class="d-none">Kirim</button>
                    </b-form>
                </validation-observer>
            </div>

            <template v-slot:modal-footer>
                <div class="w-100 m-0 text-center">
                    <button class="btn btn-md btn-primary" @click="submitForm()">
                        Kirim
                    </button>
                    <button class="btn btn-md btn-outline-danger border-0" @click="hideModal('bv-modal-weighing-form','form')">
                        Batal
                    </button>
                </div>
            </template>
        </b-modal>

        <!-- Modal Category -->
        <b-modal ref="bv-modal-category-form" :title="modalTitleNewAdd" footer-class="p-2" no-close-on-backdrop>
            <div class="d-block text-center px-3 py-2">
                <validation-observer ref="observerCategory" v-slot="{ invalid }" tag="form" @submit.prevent="submitNeweighingCategoryOptionForm()">
                    <b-form @submit.prevent="submitNeweighingCategoryOptionForm">
                        <div class="row bg-light rmb-20">
                            <div class="col-md-12">
                                <!-- Name -->
                                <b-form-group id="group-name" label="Nama:" label-for="name" class="text-left">
                                    <validation-provider mode="passive" name="Nama" :rules="{required: true, unique: { url: urlWeighingCategoryCheckNameExist, id: form.id }}" v-slot="{ errors }">
                                        <b-form-input
                                            id="name"
                                            v-model="formCategory.name"
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
                                            placeholder="Deskripsikan"
                                            v-model="formCategory.description"
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
                    <button class="btn btn-sm btn-primary" @click="submitNeweighingCategoryOptionForm()">
                        Kirim
                    </button>
                    <button class="btn btn-sm btn-outline-danger border-0" @click="hideModal('bv-modal-category-form')">
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
import Datepicker from 'vue2-datepicker'
import { Notification, MessageBox } from 'element-ui'
import { BModal, BTable, BInputGroup, BPagination, BFormRadioGroup, BForm, BFormFile, BTooltip, BFormGroup, BFormInput, BFormCheckbox, BFormTextarea, BFormSelect } from 'bootstrap-vue'
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
        BFormFile,
        BTooltip,
        BFormGroup,
        BFormInput,
        BFormTextarea,
        BFormCheckbox,
        BFormRadioGroup,
        Notification,
        MessageBox,
        vSelect,
        Datepicker,
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
        urlSupplierOptions: {
            required: true,
            type: String,
        },
        urlEmployeeOptions: {
            required: true,
            type: String,
        },
        urlWeighingCategoryData: {
            required: true,
            type: String,
        },
        urlWeighingCategoryStore: {
            required: true,
            type: String,
        },
        urlWeighingCategoryCheckNameExist: {
            required: true,
            type: String,
        },
        newData: {
            required: false,
            type: Number,
        },
        newStatus: {
            required: false,
            type: String,
        },
        newChanged: {
            required: false,
            type: Boolean,
        },
    },
    data() {
        return {
            items: [],
            new_data: null,
            new_status : null,
            previewId: [],
            supplierOptions: [],
            employeeOptions: [],
            weighingCategoryOptions: [],
            form: {
                item: [],
                weighing_category_id: null,
                do_code: null,
                first_operator_by: null,
                second_operator_by: null,
                receiper: null,
                driver_name: null,
                supplier_code: null,
                supplier_name : null,
                first_number_plate : null,
                second_number_plate : null,
                first_datetime : null,
                second_datetime : null,
                first_weight : null,
                second_weight : null,
                tolerance_weight : null,
                tolerance_reason : null,
                file : null,
                file_prev : null,
                url: this.urlStore,
                method: 'POST'
            },
            formCategory: {
                id: null,
                name: null,
                description: null,
                url: this.urlWeighingCategoryStore,
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
                { key: 'weighing_category_name', label: 'Kategori', thStyle: 'text-align: left;', tdClass: 'custom-cell text-left', sortable: true},
                { key: 'item_name', label: 'Barang', thStyle: 'text-align: left;', tdClass: 'custom-cell text-left', sortable: true},
                { key: 'supplier_name', label: 'Supplier/Pembeli', thStyle: 'text-align: left;', tdClass: 'custom-cell text-left', sortable: true},
                { key: 'do_code', label: 'Surat Jalan', thStyle: 'text-align: left;', tdClass: 'custom-cell text-left', sortable: true},
                { key: 'netto_weight', label: 'Total Berat', thStyle: 'text-align: center;', tdClass: 'custom-cell text-center' , sortable: true},
                { key: 'first_datetime', label: 'Tanggal Penimbangan', thStyle: 'text-align: center;', tdClass: 'custom-cell text-center' , sortable: true},
                { key: 'stage', label: 'Status', thStyle: 'text-align: center;', tdClass: 'custom-cell text-center' , sortable: true},
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
                'Nama': 'weighing_category_name',
                'Surat Jalan': 'do_code',
                'Nomor Mobil': 'number_plate',
                'Total Berat': 'netto_weight',
                'Tanggal Penimbangan' : {
                    field: 'first_datetime',
                    callback: (value) => {
                        return moment(value).format('DD/MM/YYYY hh:mm');
                    }
                },
                'Operator': 'operator_by_name',
            },
            json_meta: [
                [
                    {
                        'key': 'charset',
                        'value': 'utf-8'
                    }
                ]
            ],
            fileName: 'Penimbangan-' + moment().format("DD-MM-YYYY-HHMM") + '.xls',
        }
    },
    filters: {
        moment_date: function (date) {
            return moment(date).format('DD/MM/YYYY');
        },
        moment_datetime: function (date) {
            return moment(date).format('DD/MM/YYYY hh:mm');
        }
    },
    mounted(){
        this.loadSupplierOptions()
        this.loadEmployeeOptions()
        this.loadWeighingCategoryOptions()
        this.changedDetection()
    },
    methods: {
        formatNumber(value) {
            let val = (value/1).toFixed(0).replace('.', ',')
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
        },
        changedDetection(){
            this.new_data = this.newData
            this.new_status = this.newStatus
            if(this.newChanged) this.queryParams.page = 1
            var data = this
            setTimeout(function(){
                data.new_data = null
                data.new_status = null
            },5000);
        },
        setCount(type, operator, number){
            var count = 0;
            if(type=="first") {
                count = parseFloat(this.form.first_weight)
            }else if(type=="second") {
                count = parseFloat(this.form.second_weight)
            }else{
                count = parseFloat(this.form.tolerance_weight)
            }

            count = (isNaN(count)) ? 0 : count;
            if(operator=="+"){
                count = count + number;
            }else{
                count = count - number;
            }
            count = (count<0) ? 0 : count

            if(type=="first") {
                this.form.first_weight = parseFloat(count)
            }else if(type=="second") {
                this.form.second_weight = parseFloat(count)
            }else{
                this.form.tolerance_weight = parseFloat(count)
            }

        },
        disabledDateAfterToday(date){
            return date > new Date();
        },
        rowClass(item, type) {
            if (!item || type !== 'row') return
            if (item.id == this.new_data)  return 'table-warning'
        },
        checkQuantity(quantity, source){
            if (quantity<0) {
                if (quantity!=='') {
                    this.$notify({
                        showClose: true,
                        title: 'Warning',
                        message: 'Berat tidak boleh minus..!!!',
                        type: 'warning'
                    });
                }
                if(source=='first') {
                    this.form.first_weight = null
                }else if(source=='second') {
                    this.form.second_weight = null
                }else{
                    this.form.tolerance_weight = null
                }
            }
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
            this.modalTitle = 'Tambah Penimbangan'
            this.$refs['bv-modal-weighing-form'].show()
            this.form.item = []
            this.form.weighing_category_id = null
            this.form.do_code = null
            this.form.first_operator_by = null
            this.form.second_operator_by = null
            this.form.receiper = null
            this.form.driver_name = null
            this.form.supplier_code = null
            this.form.supplier_name  = null
            this.form.first_number_plate  = null
            this.form.second_number_plate  = null
            this.form.first_datetime  = null
            this.form.second_datetime  = null
            this.form.first_weight  = 0
            this.form.second_weight  = 0
            this.form.tolerance_weight  = 0
            this.form.tolerance_reason  = null
            this.form.file  = null
            this.form.file_prev  = null
            this.form.url = this.urlStore
            this.form.method = 'POST'
        },
        editForm(item){
            this.modalTitle = 'Ubah Penimbangan'
            this.$refs['bv-modal-weighing-form'].show()
            this.form.item = item
            this.form.weighing_category_id = item.weighing_category_id
            this.form.do_code = item.do_code
            this.form.first_operator_by = item.first_operator_by
            this.form.second_operator_by = item.second_operator_by
            this.form.receiper = item.receiper
            this.form.driver_name = item.driver_name
            this.form.supplier_code = item.supplier_code
            this.form.supplier_name  = item.supplier_name
            this.form.first_number_plate  = item.first_number_plate
            this.form.second_number_plate  = item.second_number_plate
            this.form.first_datetime  = item.first_datetime
            this.form.second_datetime  = item.second_datetime
            this.form.first_weight  = item.first_weight
            this.form.second_weight  = item.second_weight
            this.form.tolerance_weight  = item.tolerance_weight
            this.form.tolerance_reason  = item.tolerance_reason
            this.form.file  = null
            this.form.file_prev  = item.file
            this.form.url =  item.url_edit
            this.form.method =  'PUT'
        },
        preview(item){
            this.modalTitle = 'Informasi Penimbangan'
            this.$refs['bv-modal-preview'].show()
            this.previewId = item.id
        },
        destroy(item) {
            this.$confirm('Apakah Anda yakin ingin menghapus data dari daftar penimbangan ?', 'Peringatan', {
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
        async submitForm()  {
            const isValid = await this.$refs.observer.validate();
            if (!isValid) {
                return
            }

            let formData = new FormData();
            formData.append('weighing_category_id', this.form.weighing_category_id);
            formData.append('do_code', this.form.do_code);
            formData.append('first_operator_by', this.form.first_operator_by);
            formData.append('second_operator_by', this.form.second_operator_by);
            formData.append('receiper', this.form.receiper);
            formData.append('driver_name', this.form.driver_name);
            formData.append('supplier_code', this.form.supplier_code);
            formData.append('supplier_name', this.form.supplier_name);
            formData.append('first_number_plate', this.form.first_number_plate);
            formData.append('second_number_plate', this.form.second_number_plate);
            formData.append('first_datetime', this.form.first_datetime);
            formData.append('second_datetime', this.form.second_datetime);
            formData.append('first_weight', this.form.first_weight);
            formData.append('second_weight', this.form.second_weight);
            formData.append('tolerance_weight', this.form.tolerance_weight);
            formData.append('tolerance_reason', this.form.tolerance_reason);
            formData.append('file', this.form.file);

            if(this.form.method=='PUT'){
                formData.append('_method', 'PUT');
            }

            axios.post(this.form.url,
                formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then(response => {
                    this.hideModal('bv-modal-weighing-form', 'process')
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
                        this.$message({
                            showClose: true,
                            message: 'Oops, Sepertinya error nih, mohon dicoba kembali ya!',
                            type: 'error'
                        });
                    }
                    if (errorResponse.status == 422) {
                        this.$notify({
                            title: 'warning',
                            message: errorResponse.data.message,
                            type: 'warning',
                        })
                    }
                });

        },
        downloadFile(item){
             axios({
                url: item.url_download,
                method: 'POST',
                responseType: 'blob',
                params: {
                    file_name: item.file,
                },
            }).then((response) => {
                var fileURL = window.URL.createObjectURL(new Blob([response.data]));
                var fileLink = document.createElement('a');
                fileLink.href = fileURL;
                fileLink.setAttribute('download', item.file);
                document.body.appendChild(fileLink);
                fileLink.click();
            });
        },

        // Supplier
        loadSupplierOptions() {
            axios.get(this.urlSupplierOptions)
            .then(response => {
                this.supplierOptions = response.data.data
                this.supplierOptions.unshift({ code: null, name: 'Pilih Supplier' })
            })
            .catch(error => console.log(error));
        },

        // Employee
        loadEmployeeOptions() {
            axios.get(this.urlEmployeeOptions, { params:{department_id:'TB'}})
            .then(response => {
                this.employeeOptions = response.data.data
                this.employeeOptions.unshift({ nik: null, detail: 'Pilih Karyawan' })
            })
            .catch(error => console.log(error));
        },

        // Weighing Category
        loadWeighingCategoryOptions(){
            axios.get(window.location.origin + '/wh/weighing-category/api/v1/weighing-category-options')
            .then(response => {
                this.weighingCategoryOptions = response.data.data
                this.weighingCategoryOptions.unshift({ id: null, name: 'Pilih Kategori' })
            })
            .catch(error => console.log(error));
        },
        AddNewWeighingCategoryOptions:function(index){
            this.modalTitleNewAdd = 'Tambah Kategori Penimbangan'
            this.formCategory.url = this.urlWeighingCategoryStore
            this.formCategory.id = null
            this.formCategory.name = null
            this.formCategory.description = null
            this.formCategory.method = 'POST'
            this.$refs['bv-modal-category-form'].show()
        },
        async submitNeweighingCategoryOptionForm() {
            const isValid = await this.$refs.observerCategory.validate();
            if (!isValid) {
                return
            }

            axios({
                method: this.formCategory.method,
                url: this.formCategory.url,
                data: {
                    name: this.formCategory.name,
                    description: this.formCategory.description,
                }
            })
                .then(response => {
                    this.hideModal('bv-modal-category-form', 'process')
                    this.loadWeighingCategoryOptions()
                    this.$notify({
                        title: 'Sukses',
                        message: response.data.message,
                        type: 'success',
                    })
                    this.form.weighing_category_id = response.data.id
                })
                .catch(error => {
                    const errorResponse = error.response

                    if (errorResponse.status !== 422) {
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
