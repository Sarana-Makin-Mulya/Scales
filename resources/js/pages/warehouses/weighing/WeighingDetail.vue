<template>
    <div>
        <!-- Data Not Found -->
        <div class="d-block text-center px-1 py-1" v-if="row==0">
            <section class="content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="error-content">
                            <h1 class="headline text-danger"> 404</h1>
                            <h3><i class="fas fa-exclamation-triangle text-danger"></i> Oops! Data tidak ditemukan.</h3>
                            <p>
                                Mohon maaf aktivitas anda terganggu, silahkan klik ok untuk kembali ke halaman sebelumnya
                            </p>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <div class="d-block text-center px-1 py-1" v-if="row>0">
             <!-- Information -->
                <div class="row">
                    <div class="col-sm-2 pb-1 text-left">Kategori</div>
                    <div class="col-sm-10 pb-1 text-left">: {{ detail.weighing_category_name }}</div>
                    <div class="col-sm-2 pb-1 text-left" v-if="detail.junk_item_spk_code_text!=null">SPK</div>
                    <div class="col-sm-10 pb-1 text-left" v-if="detail.junk_item_spk_code_text!=null">: {{ detail.junk_item_spk_code_text }}</div>
                    <div class="col-sm-2 pb-1 text-left" v-if="detail.purchase_order_code_text!=null">PO</div>
                    <div class="col-sm-10 pb-1 text-left" v-if="detail.purchase_order_code_text!=null">: {{ detail.purchase_order_code_text }}</div>
                    <div class="col-sm-2 pb-1 text-left">Barang</div>
                    <div class="col-sm-10 pb-1 text-left">: {{ detail.item_name }}</div>
                    <div class="col-md-2 pb-1 text-left">
                        <span v-if="detail.junk_item_spk_code==null">Supplier</span>
                        <span v-else>Pembeli/Penadah</span>
                    </div>
                    <div class="col-md-10 pb-1 text-left">: {{ detail.supplier_name }}</div>
                    <div class="col-md-2 pb-1 text-left">Surat Jalan</div>
                    <div class="col-md-10 pb-1 text-left">: {{ detail.do_code }}</div>
                    <div class="col-md-2 pb-1 text-left">Nama Supir </div>
                    <div class="col-md-10 pb-1 text-left">: {{ detail.driver_name }} </div>
                    <div class="col-md-2 pb-1 text-left" v-if="detail.receiper!=null">Penerima</div>
                    <div class="col-md-10 pb-1 text-left" v-if="detail.receiper!=null">: {{ detail.receiper }}</div>
                    <div class="col-md-2 pb-1 text-left">Status</div>
                    <div class="col-md-10 pb-1 text-left">:
                        <span v-if="detail.stage==1">Penimbangan Pertama</span>
                        <span v-if="detail.stage==2">Penimbangan Kedua</span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 p-2 table-responsive-lg mb-0">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr class="bg-gray-light">
                                    <th class="text-left pl-2" scope="col" width="15%">Penimbangan</th>
                                    <th class="text-center" scope="col" width="15%">No Polisi</th>
                                    <th class="text-center" scope="col" width="20%">Tanggal</th>
                                    <th class="text-left" scope="col" width="28%">Operator</th>
                                    <th class="text-center" scope="col" width="12%">Berat</th>
                                    <th class="text-center" scope="col" width="10%">Satuan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-left pl-2">Pertama</td>
                                    <td class="text-center">{{ detail.first_number_plate }}</td>
                                    <td class="text-center">{{ detail.first_datetime | moment_datetime }}</td>
                                    <td class="text-left">{{ detail.first_operator_by_name }}</td>
                                    <td class="text-center">{{ formatNumber(detail.first_weight) }}</td>
                                    <td class="text-center">Kg</td>
                                </tr>
                                <tr v-if="detail.stage==2">
                                    <td class="text-left pl-2">Kedua</td>
                                    <td class="text-center">{{ detail.second_number_plate }}</td>
                                    <td class="text-center">{{ detail.second_datetime | moment_datetime }}</td>
                                    <td class="text-left">{{ detail.second_operator_by_name }}</td>
                                    <td class="text-center">{{ formatNumber(detail.second_weight) }}</td>
                                    <td class="text-center">Kg</td>
                                </tr>
                                <tr v-if="detail.tolerance_weight>0">
                                    <td class="text-left pl-2">Toleransi</td>
                                    <td class="text-left pl-2" colspan="3">Alasan: {{ detail.tolerance_reason }}</td>
                                    <td class="text-center">{{ formatNumber(detail.tolerance_weight) }}</td>
                                    <td class="text-center">Kg</td>
                                </tr>
                                <tr v-if="detail.stage==2">
                                    <td class="text-left pl-2">Berat Netto</td>
                                    <td class="text-left pl-2" colspan="3"></td>
                                    <td class="text-center">{{ formatNumber(detail.netto_weight) }}</td>
                                    <td class="text-center">Kg</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- <div class="row" v-if="detail.file!=null">
                    <div class="col-md-2 pb-1 text-left">
                        File
                    </div>
                    <div class="col-md-10 pb-1 text-left">
                            <a href="#"
                                @click.prevent="downloadFile(detail.file)"
                                data-toggle="tooltip"
                                title="Download file">
                                {{ detail.file }}
                            </a>
                            <small id="passwordHelpBlock" class="form-text text-muted">
                                Silahkan klik untuk download
                            </small>
                        </div>
                    </div>
                </div> -->

                <div class="row">
                    <!-- <div class="col-md-2 pb-1 text-left">Jenis Input</div>
                    <div class="col-md-10 pb-1 text-left"> : {{ detail.input_type }}</div> -->
                    <!-- <div class="col-md-2 pb-1 text-left">Input Laporan</div>
                    <div class="col-md-10 pb-1 text-left"> : {{ detail.issued_by_name }}</div>
                    <div class="col-md-2 pb-1 text-left">Tanggal Input</div>
                    <div class="col-md-10 pb-1 text-left"> : {{ detail.issue_date | moment_datetime }}</div> -->
                </div>
        </div>
    </div>
</template>

<script>
import moment from 'moment'
import  {BTable} from 'bootstrap-vue'


export default {
    components: {
        BTable,
    },
    props: {
        id: {
            required: false,
            type: Number,
        },
        notifId: {
            required: false,
            type: Number,
        },

    },
    data() {
        return {
            row: null,
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
        this.fetchData(this.id)
    },
    methods: {
        tabDataLink(tab){
            this.tabData = tab;
        },
        formatNumber(value) {
            let val = (value/1).toFixed(0).replace('.', ',')
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
        },
        fetchData(id){
             axios.get(window.location.origin + `/wh/weighing/api/v1/detail-weighing/${id}`)
            .then(response => {
                if(response.data=="404"){
                    this.row = 0
                }else{
                    this.detail = response.data.data
                    this.row = 1
                }
            })
            .catch(error => console.log(error));
        },
        downloadFile(file){
             axios({
                url: window.location.origin + `/wh/weighing/download-weighing`,
                method: 'POST',
                responseType: 'blob',
                params: {
                    file_name: file,
                },
            }).then((response) => {
                var fileURL = window.URL.createObjectURL(new Blob([response.data]));
                var fileLink = document.createElement('a');
                fileLink.href = fileURL;
                fileLink.setAttribute('download', file);
                document.body.appendChild(fileLink);
                fileLink.click();
            });
        },
    }
}
</script>

