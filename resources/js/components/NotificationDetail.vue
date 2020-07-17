<template>
    <div v-if="item.length>0">
        <div class="dropdown-divider"></div>
            <span v-for="notif in item" :key="notif.id">
                <a
                    href="#"
                    @click.prevent="preview(notif)"
                    class="dropdown-item"
                >
                    <div class="media">
                    <div class="media-body">
                        <p class="text-sm"><i class="fas fa-envelope mr-2 text-xs"></i>{{ notif.description }}</p>
                        <p class="text-sm text-muted"><i class="far fa-calendar-alt mr-1"></i> {{ notif.info }}</p>
                    </div>
                    </div>
                </a>
                <div class="dropdown-divider"></div>
            </span>
            <a :href="urlNotification" class="dropdown-item dropdown-footer">See All {{ notification_title }}</a>

            <!-- Modal Preview -->
            <b-modal ref="bv-modal-preview" :title="modalTitle" footer-class="p-2" :size="modalSize" ok-only no-close-on-backdrop>

                <junk-item-request-detail
                    :code = "previewData.transaction_code"
                    :notif-id = "previewData.id"
                    v-if="previewData.transaction_type=='jr'">
                </junk-item-request-detail>

                <junk-item-price-detail
                    :id = "previewData.transaction_id"
                    :notif-id = "previewData.id"
                    v-if="previewData.transaction_type=='ji'">
                </junk-item-price-detail>

                <!-- Purchase Request Rejected -->
                <purchase-request-rejected-detail
                    :code = "previewData.transaction_code"
                    :notif-id = "previewData.id"
                    v-if="previewData.transaction_type=='pr-rej'"
                >
                </purchase-request-rejected-detail>

                <!-- Service Order -->
                <service-order-detail
                 :code = "previewData.transaction_code"
                 :notif-id = "previewData.id"
                 v-if="previewData.transaction_type=='so'"
                >
                </service-order-detail>

                <!-- Purchase Request -->
                <service-request-detail
                 :code = "previewData.transaction_code"
                 :notif-id = "previewData.id"
                 v-if="previewData.transaction_type=='sr'"
                >
                </service-request-detail>

                <!-- Purchase Request -->
                <purchase-request-detail
                 :code = "previewData.transaction_code"
                 :notif-id = "previewData.id"
                 v-if="previewData.transaction_type=='pr'"
                >
                </purchase-request-detail>

                <!-- Purchase Order : PO -->
                <purchase-order-detail
                :code = "previewData.transaction_code"
                :notif-id = "previewData.id"
                v-if="previewData.transaction_type=='po-item'"
                >
                </purchase-order-detail>

                <!-- Delivery Order -->
                <delivery-order-detail
                :code = "previewData.transaction_code"
                :deliveryOrderItemId = "previewData.delivery_order_item_id"
                :notif-id = "previewData.id"
                v-if="previewData.transaction_type=='do'"
                >
                </delivery-order-detail>

                <!-- Stock Adjustment -->
                <stock-adjustment-detail
                :code = "previewData.transaction_code"
                :stockAdjustmentItemId = previewData.stock_adjustment_item_id
                :status = previewData.entry_status
                :notif-id = "previewData.id"
                v-if="previewData.transaction_type=='sa'"
                >
                </stock-adjustment-detail>

                <!-- ----------------------------------------------  -->

                <!-- Goods Request -->
                <goods-request-detail
                :code = "previewData.transaction_code"
                :icGoodsRequestItemId = "previewData.ic_goods_request_item_id"
                :icGoodsRequestItemOutId = "previewData.ic_goods_request_item_out_id"
                v-if="previewData.transaction_type=='GR'"
                >
                </goods-request-detail>

                <!-- Goods Return -->
                <goods-return-detail
                :code = previewData.transaction_code
                v-if="previewData.transaction_type=='ri'"
                :notif-id = "previewData.id"
                >
                </goods-return-detail>

                <!-- Button Purpose -->
                <template v-slot:modal-footer v-if="btnPurpose">
                    <div class="w-100 m-0 text-center">
                        <!-- <a :href="urlPurpose" button class="btn btn-md btn-primary">
                            Proses Sekarang
                        </a> -->

                         <a
                            href="#"
                            @click.prevent="processLink()"
                            class="btn btn-md btn-primary"
                            data-toggle="tooltip"
                            title="Klik untuk melakukan proses"
                        >
                            Proses Sekarang
                        </a>

                        <button class="btn btn-md btn-outline-danger border-0" @click="hideModal('bv-modal-preview')">
                            Tutup
                        </button>
                    </div>
                </template>
            </b-modal>
    </div>
</template>
<script>
import { Notification, MessageBox } from 'element-ui'
import { BModal, BTable, BPagination, BForm, BFormGroup, BFormSelect, BFormInput} from 'bootstrap-vue'
export default {
    components: {
        BModal,
        BTable,
        BPagination,
        BFormSelect,
        BForm,
        BFormGroup,
        BFormInput,
        MessageBox,
    },
    props: {
        type: {
            required: true,
            type: String,
        },
    },
    data() {
        return {
            item : [],
            urlNotification: window.location.origin + '/general/notification',
            notification_title: "Notifications",
            modalTitle: undefined,
            modalSize: 'xl',
            btnPurpose:false,
            urlPurpose:null,
            setLocalStorageName: null,
            setLocalStorageValue: null,
            previewData :{
                transaction_id : null,
                transaction_code : null,
                transaction_type : null,
            }
        }
    },
    filters: {
        moment_datetime: function (date) {
            return moment(date).format('DD MMMM YYYY HH:mm');
        }
    },
    mounted(){
        this.getNotification()
        this.setNotificationType(this.type)
        //this.todo()
    },
    methods: {
        todo: function(){
            setInterval(function(){
                this.getNotification()
            }.bind(this), 5000);
        },
        getNotification() {
            axios.get(window.location.origin + '/general/socket/api/v1/notification?type=' + this.type)
            .then(response => {
                this.item = response.data.data
            })
            .catch(error => console.log(error));
        },
        setNotificationType(type){
            if(type=="reminder"){
                this.urlNotification=window.location.origin + '/general/notification?type=reminder'
                this.notification_title="Reminders"
            }
        },
        processLink(){
            if(this.btnPurpose==1){
                if(this.setLocalStorageValue!=null){
                    localStorage.setItem(this.setLocalStorageName, this.setLocalStorageValue);
                }
                window.location = this.urlPurpose;
            }
        },
        preview(notif){
            this.modalTitle  = notif.description
            this.previewData = notif
            this.modalSize   = (notif.transaction_type=='SR') ? 'lg' : 'xl'
            switch (notif.transaction_type) {
                case 'pr':
                    var urlPr = window.location.origin + "/po/purchase-request/api/v1/purchase-request-options"
                    axios.get(urlPr, { params:{filter:'po', purchase_type:1, purchase_request_code:notif.transaction_code}})
                    .then(response => {
                        this.itemRequestByCode = response.data.data
                        var item_request = response.data.data
                        if(item_request.length>0){
                            this.btnPurpose = 1;
                            this.urlPurpose = window.location.origin + "/po/purchase-order?tab=request";
                            this.setLocalStorageName  = "notification-purchase-request-code";
                            this.setLocalStorageValue = notif.transaction_code;
                            //localStorage.setItem("notification-purchase-request-code", notif.transaction_code);
                        }else{
                            this.btnPurpose = null;
                            this.urlPurpose = null;
                            this.setLocalStorageName  = "notification-purchase-request-code";
                            this.setLocalStorageValue = null;
                            //localStorage.setItem("notification-purchase-request-code", null);
                        }

                    })
                    .catch(error => console.log(error));
                break;
                case 'sr':
                    var urlPr = window.location.origin + "/po/service-request/api/v1/check-request-detail/"
                    axios.get(urlPr + notif.transaction_code)
                    .then(response => {
                        if(response.data.data!=null){
                            this.btnPurpose = 1;
                            this.urlPurpose = window.location.origin + "/po/service-order?tab=request";
                            this.setLocalStorageName  = "notification-service-request-code";
                            this.setLocalStorageValue = notif.transaction_code;
                            // localStorage.setItem("notification-service-request-code", notif.transaction_code);
                        }else{
                            this.btnPurpose = 0;
                            this.urlPurpose = null;
                            this.setLocalStorageName  = "notification-service-request-code";
                            this.setLocalStorageValue = null;
                            // localStorage.setItem("notification-service-request-code", null);
                        }
                    })
                    .catch(error => console.log(error));
                break;

                case 'ji':
                    var urlPr = window.location.origin + "/po/junk-item/api/v1/check-detail-junk-item-price/"
                    axios.get(urlPr + notif.transaction_id)
                    .then(response => {
                        if(response.data.data!=null){
                            this.btnPurpose = 1;
                            this.urlPurpose = window.location.origin + "/po/junk-item/approvals-price";
                            // localStorage.setItem("notification-junk-item-prices-id", notif.transaction_id);
                            this.setLocalStorageName  = "notification-junk-item-prices-id";
                            this.setLocalStorageValue = notif.transaction_id;
                        }else{
                            this.btnPurpose = 0;
                            this.urlPurpose = null;
                            this.setLocalStorageName  = "notification-junk-item-prices-id";
                            this.setLocalStorageValue = null;
                            // localStorage.setItem("notification-junk-item-prices-id", null);
                        }
                    })
                    .catch(error => console.log(error));
                break;

                case 'jr':
                    var urlPr = window.location.origin + "/po/junk-item-request/api/v1/check-detail-junk-item-request-weighing/"
                    axios.get(urlPr + notif.transaction_code)
                    .then(response => {
                        if(response.data.data!=null){
                            this.btnPurpose = 1;
                            this.urlPurpose = window.location.origin + "/po/junk-item-request/weighing";
                            // localStorage.setItem("notification-junk-item-request-code", notif.transaction_code);
                            this.setLocalStorageName  = "notification-junk-item-request-code";
                            this.setLocalStorageValue = null;
                        }else{
                            this.btnPurpose = 0;
                            this.urlPurpose = null;
                            this.setLocalStorageName  = "notification-junk-item-request-code";
                            this.setLocalStorageValue = null;
                            // localStorage.setItem("notification-junk-item-request-code", null);
                        }
                    })
                    .catch(error => console.log(error));
                break;

                default  :
                    this.btnPurpose = 0;
                    this.urlPurpose = null;
                break;
            }

            this.$refs['bv-modal-preview'].show()
            this.getNotification()
        },
        hideModal(modal) {
            this.$refs[modal].hide()
        },
    }
}
</script>

