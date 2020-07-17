<template>
    <div>

        <div class="row">

                <div class="col-md-6">
                    <a
                        href="#"
                        @click.prevent="weighingSecondForm()"
                    >
                        <div class="small-box bg-danger">
                        <div class="inner">
                            <h4><i class="fas fa-arrow-right"></i> (Berat Akhir)</h4>
                            <p>Penimbangan Kedua</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-weight"></i>
                        </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-6">
                    <a
                        href="#"
                        @click.prevent="weighingFirstForm()"
                    >
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h4><i class="fas fa-arrow-left"></i> (Berat Awal)</h4>
                                <p>Penimbangan Pertama</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-plus"></i>
                            </div>
                        </div>
                    </a>
                </div>
            </div>


        <div class="card">
            <!-- Header Sub -->


            <!-- Tab -->
            <div class="card-body">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a
                        class="nav-link"
                        :class="(tab=='weighing') ? 'active' : '' "
                        id="weighing-tab"
                        data-toggle="tab"
                        href="#weighing"
                        role="tab"
                        aria-controls="weighing"
                        aria-selected="true"
                        @click="tabLink('weighing')"
                    >
                        Penimbangan
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link"
                        :class="(tab=='category') ? 'active' : '' "
                        id="category-tab"
                        data-toggle="tab"
                        href="#category"
                        role="tab"
                        aria-controls="category"
                        aria-selected="false"
                        @click="tabLink('category')"
                    >
                        Kategori
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link"
                        :class="(tab=='item') ? 'active' : '' "
                        id="item-tab"
                        data-toggle="tab"
                        href="#item"
                        role="tab"
                        aria-controls="item"
                        aria-selected="false"
                        @click="tabLink('item')"
                    >
                        Barang
                    </a>
                </li>
                </ul>

                <div class="tab-content bg-light p-2" id="myTabContent">
                    <!-- Tab Content Weighing -->
                    <div
                        class="tab-pane fade"
                        :class="(tab=='weighing') ? 'show active' : '' "
                        id="weighing"
                        role="tabpanel"
                        aria-labelledby="weighing-tab"
                    >
                        <weighing-table-records
                            :url-data="urlData"
                            :url-store="urlStore"
                            :url-supplier-options="urlSupplierOptions"
                            :url-employee-options="urlEmployeeOptions"
                            :url-weighing-category-data="urlWeighingCategoryData"
                            :url-weighing-category-store="urlWeighingCategoryStore"
                            :url-weighing-category-check-name-exist="urlWeighingCategoryCheckNameExist"
                            v-if="tab=='weighing'"
                        ></weighing-table-records>
                    </div>

                    <!-- Tab Content Base on Category-->
                    <div
                        class="tab-pane fade dnone"
                        :class="(tab=='category') ? 'show active' : '' "
                        id="category"
                        role="tabpanel"
                        aria-labelledby="category-tab"
                    >
                        <weighing-category-table-records
                            :url-data="urlWeighingCategoryData"
                            :url-store="urlWeighingCategoryStore"
                            :url-check-name-exist="urlWeighingCategoryCheckNameExist"
                            v-if="tab=='category'"
                        ></weighing-category-table-records>
                    </div>

                    <!-- Tab Content Base on Category-->
                    <div
                        class="tab-pane fade dnone"
                        :class="(tab=='item') ? 'show active' : '' "
                        id="item"
                        role="tabpanel"
                        aria-labelledby="item-tab"
                    >
                        <weighing-item-table-records
                            :url-data="urlWeighingItemData"
                            :url-store="urlWeighingItemStore"
                            :url-check-name-exist="urlWeighingItemCheckNameExist"
                            v-if="tab=='item'"
                        ></weighing-item-table-records>
                    </div>
                </div>
            </div>
        </div>

        <!-- First Weighing -->
        <b-modal ref="bv-modal-weighing-first-form" :title="modalTitle" footer-class="p-2" size="xl" no-close-on-backdrop hide-header-close>
            <div class="d-block text-center px-2 py-0">
                <validation-observer ref="observerFirstForm" v-slot="{ invalid }" tag="form" @submit.prevent="submitFirstForm()">
                    <b-form @submit.prevent="submitFirstForm">
                            <div class="row">
                                <div class="col-md-7 p-2">
                                <!-- B : LEFT -->
                                    <!-- CATEGORY -->
                                    <div class="row bg-light rmb-20">
                                        <div class="col-md-12 p-2">
                                            <b-form-group id="group-category" label="Kategori :" label-for="category" class="text-left">
                                                <div @click="AddNewWeighingCategoryOptions()"
                                                    style="color:#38c172;font-weight:bold;cursor:pointer;margin-top:-24px;margin-left:73px;width:15px"
                                                    id='tooltip-add-category'
                                                >
                                                    <i class="far fa-plus-square"></i>
                                                    <b-tooltip placement="top" target="tooltip-add-category" title="Tambah Master Kategori Penimbangan"></b-tooltip>
                                                </div>
                                                <validation-provider mode="passive" name="Kategori" :rules="{required: true}" v-slot="{ errors }">
                                                    <v-select
                                                        label="name"
                                                        v-model="firstForm.weighing_category_id"
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
                                    </div>
                                    <!-- SPK BS -->
                                    <div class="row bg-light rmb-20" v-if="firstForm.weighing_category_id==1">
                                        <div class="col-md-4 p-2">
                                            <b-form-group id="group-junk-item-request-code" label="SPK BS :" label-for="junk_item_request_code" class="text-left">
                                                <validation-provider mode="passive" name="SPK BS" :rules="{required: true}" v-slot="{ errors }">
                                                    <v-select
                                                        label="name"
                                                        v-model="firstForm.junk_item_request_code"
                                                        :options="SpkBsOptions"
                                                        :reduce="name => name.id"
                                                    >
                                                        <template slot="no-options">
                                                           SPK BS Tidak ditemukan
                                                        </template>
                                                    </v-select>
                                                    <span class="form-error-message" v-if="errors[0]">{{ errors[0] }}</span>
                                                </validation-provider>
                                            </b-form-group>
                                        </div>
                                        <div class="col-md-8 p-2">
                                            <b-form-group id="group-junk-item-request-detail-id" label="Barang :" label-for="junk_item_request_detail_id" class="text-left">
                                                <validation-provider mode="passive" name="Barang" :rules="{required: true}" v-slot="{ errors }">
                                                    <v-select
                                                        label="name"
                                                        v-model="firstForm.junk_item_request_detail_id"
                                                        :options="SpkBsItemOptions"
                                                        :reduce="name => name.id"
                                                    >
                                                        <template slot="no-options">
                                                           Barang Tidak ditemukan
                                                        </template>
                                                    </v-select>
                                                    <span class="form-error-message" v-if="errors[0]">{{ errors[0] }}</span>
                                                </validation-provider>
                                            </b-form-group>
                                        </div>
                                    </div>
                                    <!-- PO -->
                                    <div class="row bg-light rmb-20"  v-if="firstForm.weighing_category_id==2">
                                        <div class="col-md-4 p-2">
                                            <b-form-group id="group-purchase-order-code" label="PO :" label-for="purchase_order_code" class="text-left">
                                                <validation-provider mode="passive" name="PO" :rules="{required: true}" v-slot="{ errors }">
                                                    <v-select
                                                        label="name"
                                                        v-model="firstForm.purchase_order_code"
                                                        :options="PoOptions"
                                                        :reduce="name => name.id"
                                                    >
                                                        <template slot="no-options">
                                                           PO Tidak ditemukan
                                                        </template>
                                                    </v-select>
                                                    <span class="form-error-message" v-if="errors[0]">{{ errors[0] }}</span>
                                                </validation-provider>
                                            </b-form-group>
                                        </div>
                                        <div class="col-md-8 p-2">
                                            <b-form-group id="group-purchasing-purchase-order-item-id" label="Barang :" label-for="purchasing_purchase_order_item_id" class="text-left">
                                                <validation-provider mode="passive" name="Barang" :rules="{required: true}" v-slot="{ errors }">
                                                    <v-select
                                                        label="name"
                                                        v-model="firstForm.purchasing_purchase_order_item_id"
                                                        :options="PoItemOptions"
                                                        :reduce="name => name.id"
                                                    >
                                                        <template slot="no-options">
                                                           Barang Tidak ditemukan
                                                        </template>
                                                    </v-select>
                                                    <span class="form-error-message" v-if="errors[0]">{{ errors[0] }}</span>
                                                </validation-provider>
                                            </b-form-group>
                                        </div>
                                    </div>
                                    <!-- OTHER -->
                                    <div class="row bg-light rmb-20"  v-if="firstForm.weighing_category_id==null || firstForm.weighing_category_id==3">
                                        <div class="col-md-12 p-2">
                                            <b-form-group id="group-do-code" label="Barang :" label-for="weighing_item_code" class="text-left">
                                                 <div @click="AddNewWeighingItemOptions()"
                                                    style="color:#38c172;font-weight:bold;cursor:pointer;margin-top:-24px;margin-left:73px;width:15px"
                                                    id='tooltip-add-item'
                                                >
                                                    <i class="far fa-plus-square"></i>
                                                    <b-tooltip placement="top" target="tooltip-add-item" title="Tambah Master Barang Penimbangan"></b-tooltip>
                                                </div>
                                                <validation-provider mode="passive" name="Barang" :rules="{required: true}" v-slot="{ errors }">
                                                    <v-select
                                                        label="name"
                                                        v-model="firstForm.weighing_item_code"
                                                        :options="weighingItemOptions"
                                                        :reduce="name => name.code"
                                                    >
                                                         <template slot="no-options">
                                                            <div @click="AddNewWeighingItemOptions()" style="color:#38c172;font-weight:bold;cursor:pointer">
                                                                <i class="far fa-plus-square"></i> Tambah Master Barang Penimbangan
                                                            </div>
                                                        </template>
                                                    </v-select>
                                                    <span class="form-error-message" v-if="errors[0]">{{ errors[0] }}</span>
                                                </validation-provider>
                                            </b-form-group>
                                        </div>
                                    </div>
                                    <!-- SUPPLIER -->
                                    <div class="row bg-light rmb-20">
                                        <div class="col-md-4 p-2 bg">
                                            <b-form-group id="group-do-code" label="Nomor surat jalan :" label-for="do_code" class="text-left">
                                                <validation-provider mode="passive" name="Nomor surat jalan" :rules="{required: true}" v-slot="{ errors }">
                                                    <b-form-input
                                                        id="do_code"
                                                        v-model="firstForm.do_code"
                                                        type="text"
                                                        placeholder=""
                                                        autocomplete="off"
                                                        class="form-control form-control-lg text-left"
                                                    ></b-form-input>
                                                    <span class="form-error-message" v-if="errors[0]">{{ errors[0] }}</span>
                                                </validation-provider>
                                            </b-form-group>
                                        </div>
                                        <div class="col-md-8 p-2 bg">
                                            <b-form-group id="group-do-code" label="Supplier :" label-for="supplier_name" class="text-left">
                                                <validation-provider mode="passive" name="Supplier" :rules="{required: true}" v-slot="{ errors }">
                                                    <b-form-input
                                                        id="supplier_name"
                                                        v-model="firstForm.supplier_name"
                                                        type="text"
                                                        placeholder=""
                                                        autocomplete="off"
                                                        class="form-control form-control-lg text-left"
                                                    ></b-form-input>
                                                    <span class="form-error-message" v-if="errors[0]">{{ errors[0] }}</span>
                                                </validation-provider>
                                            </b-form-group>
                                        </div>
                                    </div>
                                    <!-- DELIVER -->
                                    <div class="row bg-light rmb-20">
                                        <div class="col-md-4 p-2">
                                            <b-form-group id="group-do-code" label="No mobil/polisi :" label-for="first_number_plate" class="text-left">
                                                <validation-provider mode="passive" name="No mobil/polisi" :rules="{required: true}" v-slot="{ errors }">
                                                    <b-form-input
                                                        id="first_number_plate"
                                                        v-model="firstForm.first_number_plate"
                                                        type="text"
                                                        placeholder=""
                                                        autocomplete="off"
                                                        class="form-control form-control-lg text-left"
                                                    ></b-form-input>
                                                    <span class="form-error-message" v-if="errors[0]">{{ errors[0] }}</span>
                                                </validation-provider>
                                            </b-form-group>
                                        </div>
                                        <div class="col-md-8 p-2">
                                            <b-form-group id="group-do-code" label="Supir :" label-for="driver_name" class="text-left">
                                                <validation-provider mode="passive" name="Supir" :rules="{required: true}" v-slot="{ errors }">
                                                    <b-form-input
                                                        id="driver_name"
                                                        v-model="firstForm.driver_name"
                                                        type="text"
                                                        placeholder=""
                                                        autocomplete="off"
                                                        class="form-control form-control-lg text-left"
                                                    ></b-form-input>
                                                    <span class="form-error-message" v-if="errors[0]">{{ errors[0] }}</span>
                                                </validation-provider>
                                            </b-form-group>
                                        </div>
                                    </div>
                                <!-- E : LEFT -->
                            </div>
                            <div class="col-md-5 p-2">
                                <!-- B : RIGHT -->
                                    <div class="row bg-light rmb-20">
                                        <div class="col-md-12 p-2">
                                            <!-- WEIGHING -->
                                        <div class="info-box bg-gradient-success">
                                            <span class="info-box-icon"><i class="fas fa-weight-hanging"></i></span>

                                            <div class="info-box-content">
                                                <span class="info-box-number"><h1>41.410 KG</h1></span>

                                                <div class="progress">
                                                <div class="progress-bar" style="width: 100%"></div>
                                                </div>
                                                <span class="progress-description">
                                                Yohanes Setiawan - 16/07/2020 16:00
                                                </span>
                                            </div>
                                            <!-- /.info-box-content -->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row bg-light rmb-20">
                                        <div class="col-md-12 p-2">
                                            <!-- TOLERANCE -->
                                            <b-form-group id="group-do-code" label="Toleransi :" label-for="first_number_plate" class="text-left">
                                                <table width="100%" border='0' celpading="0" celspacing="0">
                                                    <tr>
                                                        <td width="20%">
                                                            <div class="btn-group btn-group-lg-left">
                                                                <button type="button" class="btn btn-weighing-lg" @click="setCountTolerance('-', 100)">-100</button>
                                                                <button type="button" class="btn btn-weighing-lg" @click="setCountTolerance('-', 10)">-10</button>
                                                                <button type="button" class="btn btn-weighing-lg" @click="setCountTolerance('-', 1)">-1</button>
                                                            </div>
                                                        </td>
                                                        <td width="*">
                                                            <validation-provider mode="passive" name="Berat Akhir" :rules="{required: true}" v-slot="{ errors }">
                                                                <b-form-input
                                                                    v-model="firstForm.tolerance_weight"
                                                                    type="number"
                                                                    placeholder="Masukan berat"
                                                                    autocomplete="off"
                                                                    @blur="checkQuantityTolerance(firstForm.tolerance_weight)"
                                                                    class="form-control form-control-lg-weighing text-center"
                                                                ></b-form-input>
                                                                <span class="form-error-message" v-if="errors[0]">{{ errors[0] }}</span>
                                                            </validation-provider>
                                                        </td>
                                                        <td width="20%">
                                                            <div class="btn-group btn-group-lg-right">
                                                                <button type="button" class="btn btn-weighing-lg" @click="setCountTolerance('+', 1)">+1</button>
                                                                <button type="button" class="btn btn-weighing-lg" @click="setCountTolerance('+', 10)">+10</button>
                                                                <button type="button" class="btn btn-weighing-lg" @click="setCountTolerance('+', 100)">+100</button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </b-form-group>
                                        </div>
                                    </div>
                                    <div class="row bg-light rmb-20">
                                        <div class="col-md-12 p-2" v-if="firstForm.tolerance_weight>0">
                                        <!-- Tolerance Reason -->
                                        <b-form-group id="group-tolerance-reason" label="Alasan Toleransi :" label-for="tolerance_reason" class="text-left">
                                            <validation-provider mode="passive" name="Alasan Toleransi" :rules="{required: true}" v-slot="{ errors }">
                                                <b-form-textarea
                                                    id="tolerance_reason"
                                                    class="form-control form-textarea"
                                                    placeholder="Alasan Toleransi"
                                                    v-model="firstForm.tolerance_reason"
                                                    rows="2"
                                                    no-resize
                                                >
                                                </b-form-textarea>
                                                <span class="form-error-message" v-if="errors[0]">{{ errors[0] }}</span>
                                            </validation-provider>
                                        </b-form-group>
                                    </div>
                                    </div>
                                <!-- E : RIGHT -->
                            </div>
                        </div>
                        <button type="submit" class="d-none">Kirim</button>
                    </b-form>
                </validation-observer>
            </div>

            <template v-slot:modal-footer>
                <div class="w-100 m-0 text-center">
                    <button class="btn btn-md btn-primary" @click="submitFirstForm()">
                        Kirim
                    </button>
                    <button class="btn btn-md btn-outline-danger border-0" @click="hideModal('bv-modal-weighing-first-form','form')">
                        Batal
                    </button>
                </div>
            </template>
        </b-modal>

        <!-- Second Weighing -->
        <b-modal ref="bv-modal-weighing-second-form" :title="modalTitle" footer-class="p-2" size="xl" no-close-on-backdrop hide-header-close>
            <div class="d-block text-center px-3 py-2">
                <validation-observer ref="observerSecondForm" v-slot="{ invalid }" tag="form" @submit.prevent="submitSecondForm()">
                    <b-form @submit.prevent="submitSecondForm">
                        <div class="row bg-light rmb-20">
                            <div class="col-md-4 p-2">
                                <!--
                                    first_number_plate: null,
                                    first_weight: null,
                                -->
                            </div>
                        </div>
                        <button type="submit" class="d-none">Kirim</button>
                    </b-form>
                </validation-observer>
            </div>

            <template v-slot:modal-footer>
                <div class="w-100 m-0 text-center">
                    <button class="btn btn-md btn-primary" @click="submitSecondForm()">
                        Kirim
                    </button>
                    <button class="btn btn-md btn-outline-danger border-0" @click="hideModal('bv-modal-weighing-second-form','form')">
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
                                    <validation-provider mode="passive" name="Nama" :rules="{required: true, unique: { url: urlWeighingCategoryCheckNameExist, id: formItem.id }}" v-slot="{ errors }">
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
                                    <validation-provider mode="passive" name="Deskripsi" :rules="{required: true }" v-slot="{ errors }">
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

        <!-- Modal Item -->
        <b-modal ref="bv-modal-item-form" :title="modalTitleNewAdd" footer-class="p-2" no-close-on-backdrop>
            <div class="d-block text-center px-3 py-2">
                <validation-observer ref="observerItem" v-slot="{ invalid }" tag="form" @submit.prevent="submitNeweighingItemOptionForm()">
                    <b-form @submit.prevent="submitNeweighingItemOptionForm">
                        <div class="row bg-light rmb-20">
                            <div class="col-md-12">
                                <!-- Name -->
                                <b-form-group id="group-name" label="Nama:" label-for="name" class="text-left">
                                    <validation-provider mode="passive" name="Nama" :rules="{required: true, unique: { url: urlWeighingCategoryCheckNameExist, id: formItem.code }}" v-slot="{ errors }">
                                        <b-form-input
                                            id="name"
                                            v-model="formItem.name"
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
                                    <validation-provider mode="passive" name="Deskripsi" :rules="{required: true }" v-slot="{ errors }">
                                        <b-form-textarea
                                            id="description"
                                            class="form-control"
                                            placeholder="Deskripsikan"
                                            v-model="formItem.description"
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
                    <button class="btn btn-sm btn-primary" @click="submitNeweighingItemOptionForm()">
                        Kirim
                    </button>
                    <button class="btn btn-sm btn-outline-danger border-0" @click="hideModal('bv-modal-item-form')">
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
        // Junk Item
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

        urlWeighingItemData: {
            required: true,
            type: String,
        },
        urlWeighingItemStore: {
            required: true,
            type: String,
        },
        urlWeighingItemCheckNameExist: {
            required: true,
            type: String,
        },
    },
    data(){
        return {
            tab:'weighing',
            weighingItemOptions: [],
            weighingCategoryOptions: [],
            SpkBsOptions: [],
            SpkBsItemOptions: [],
            PoOptions: [],
            PoItemOptions: [],
            modalTitle: undefined,
            modalTitleNewAdd: undefined,
            firstForm : {
                weighing_category_id : null,
                junk_item_request_code: null,
                junk_item_request_detail_id: null,
                purchase_order_code: null,
                purchasing_purchase_order_item_id: null,
                weighing_item_code: null,
                do_code: null,
                receiper: null,
                driver_name: null,
                supplier_code: null,
                supplier_name: null,
                first_number_plate: null,
                first_weight: null,
                tolerance_weight: null,
                tolerance_reason: null,
                url:null,
                method: 'POST',
            },
            secondForm: {
                first_number_plate: null,
                first_weight: null,
                url:null,
                method: 'POST',
            },
            formCategory: {
                id: null,
                name: null,
                description: null,
                url: this.urlWeighingCategoryStore,
                method: 'POST'
            },
            formItem: {
                id: null,
                name: null,
                description: null,
                url: this.urlWeighingItemStore,
                method: 'POST'
            },
        }
    },
    mounted(){
        this.tabDefault();
        this.loadWeighingCategoryOptions()
        this.loadWeighingItemOptions()
    },
    methods: {
        tabDefault(){
            var params = new URL(location.href).searchParams.get('tab')
            if(params!=null){ this.tab = params}else{this.tab = 'weighing'}
        },
        tabLink(tab){
            this.tab = tab;
        },

        setCountTolerance(operator, number){
            var count = 0;
            count = parseFloat(this.firstForm.tolerance_weight)
            count = (isNaN(count)) ? 0 : count;
            console.log(count);
            if(operator=="+"){
                count = count + number;
            }else{
                count = count - number;
            }
            count = (count<0) ? 0 : count

            this.firstForm.tolerance_weight = parseFloat(count)

        },

        checkQuantityTolerance(quantity){
            if (quantity<0) {
                if (quantity!=='') {
                    this.$notify({
                        showClose: true,
                        title: 'Warning',
                        message: 'Berat tidak boleh minus..!!!',
                        type: 'warning'
                    });
                }
                this.firstForm.tolerance_weight = null
            }
        },

        weighingFirstForm(){
            this.modalTitle = 'Penimbangan Pertama (Awal)'
            this.$refs['bv-modal-weighing-first-form'].show()
            this.firstForm.weighing_category_id = null
            this.firstForm.junk_item_request_code = null
            this.firstForm.junk_item_request_detail_id = null
            this.firstForm.purchase_order_code = null
            this.firstForm.purchasing_purchase_order_item_id = null
            this.firstForm.weighing_item_code = null
            this.firstForm.do_code = null
            this.firstForm.receiper = null
            this.firstForm.driver_name = null
            this.firstForm.supplier_code = null
            this.firstForm.supplier_name  = null
            this.firstForm.first_number_plate  = null
            this.firstForm.first_weight  = 0
            this.firstForm.tolerance_weight  = 0
            this.firstForm.tolerance_reason  = null
            this.firstForm.url = this.urlFirstStore
            this.firstForm.method = 'POST'
        },
        weighingSecondForm(){
            this.modalTitle = 'Penimbangan Kedua (Akhir)'
            this.$refs['bv-modal-weighing-second-form'].show()

            this.secondForm.first_number_plate  = null
            this.secondForm.first_weight  = 0

            this.secondForm.url = this.urlSecondStore
            this.secondForm.method = 'POST'
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

        async submitFirstForm()  {
            const isValid = await this.$refs.observerFirstForm.validate();
            if (!isValid) {
                return
            }
        },

        async submitSecondForm()  {
            const isValid = await this.$refs.observerSecondForm.validate();
            if (!isValid) {
                return
            }
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
                    this.firstForm.weighing_category_id = response.data.id
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

        // Weighing Item
        loadWeighingItemOptions(){
            axios.get(window.location.origin + '/wh/weighing-item/api/v1/weighing-item-options')
            .then(response => {
                this.weighingItemOptions = response.data.data
                this.weighingItemOptions.unshift({ id: null, name: 'Pilih Barang' })
            })
            .catch(error => console.log(error));
        },
        AddNewWeighingItemOptions:function(index){
            this.modalTitleNewAdd = 'Tambah Barang Penimbangan'
            this.formItem.url = this.urlWeighingItemStore
            this.formItem.code = null
            this.formItem.name = null
            this.formItem.description = null
            this.formItem.method = 'POST'
            this.$refs['bv-modal-item-form'].show()
        },
        async submitNeweighingItemOptionForm() {
            const isValid = await this.$refs.observerItem.validate();
            if (!isValid) {
                return
            }

            axios({
                method: this.formItem.method,
                url: this.formItem.url,
                data: {
                    name: this.formItem.name,
                    description: this.formItem.description,
                }
            })
                .then(response => {
                    this.hideModal('bv-modal-item-form', 'process')
                    this.loadWeighingItemOptions()
                    this.$notify({
                        title: 'Sukses',
                        message: response.data.message,
                        type: 'success',
                    })
                    this.firstForm.weighing_item_code = response.data.code
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
