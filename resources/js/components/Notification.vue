<template>
    <div>
        <a class="nav-link" data-toggle="dropdown" href="#" @click="setNotif()">
            <i :class="notification_icon"></i>
            <span :class="notification_bagde">{{ notification_row }}</span>
        </a>

        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">{{ notification_row }} {{ notification_title }}</span>
          <notification-detail :type='type'></notification-detail>
        </div>

    </div>
</template>
<script>
import { Notification, MessageBox } from 'element-ui'
export default {
    components: {
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
            notification_row : 0,
            show :0,
            notification_icon: "far fa-bell",
            notification_bagde: "badge badge-danger navbar-badge",
            notification_title: "Notifications",
        }
    },
    mounted(){
        this.getNotificationRow()
        this.getNotificationPopup()
        this.setNotificationType(this.type)
        //this.todo()
    },
    methods: {
        getNotificationRow() {
            axios.get(window.location.origin + '/general/socket/api/v1/notification-row?type=' + this.type)
            .then(response => {
                this.notification_row = response.data.notification
            })
            .catch(error => console.log(error));
        },

        getItemStock() {
            axios.get(window.location.origin + '/general/socket/api/v1/item-stock')
            .then(response => {
                var status = response.data.status
                if(status==1){
                    //console.log('nyalakan');
                   this.setLampLocation(1);
                }else{
                    //console.log('matikan');
                    this.setLampLocation(0);
                }
            })
            .catch(error => console.log(error));
        },

        setLampLocation(st) {
            var host = "http://192.168.0.103/setpin";
            var pin = 'D1';
            var state = st;

            // let formData = new FormData();
            // formData.append('pin', pin);
            // formData.append('state', state);

            //  $.ajax({
            //     url: host,
            //     type: 'POST',
            //     dataType: 'html',
            //     data: formData,
            //     cache: false,
            //     contentType: false,
            //     processData: false,
            // })

            // axios.post(host,
            //     formData, {
            //         headers: {
            //             'Content-Type': 'multipart/form-data'
            //         }
            //     })
            //     .then(response => {
            //         this.$notify({
            //             title: 'Sukses',
            //             message: response.data.message,
            //             type: 'success',
            //         })
            //     })
            //     .catch(error => {
            //         const errorResponse = error.response
            //     })
        },
        getNotificationPopup() {
            let self = this;
            axios.get(window.location.origin + '/general/socket/api/v1/notification-popup')
            .then(response => {
                var popup_data = response.data.data;
                if (popup_data.length>0) {
                    popup_data.forEach(function(data) {
                        self.$notify({
                            title: 'Notifikasi',
                            message: data.description,
                            type: 'info',
                        })
                    });
                }

            })
            .catch(error => console.log(error));
        },
        todo: function(){
            setInterval(function(){
                this.getNotificationRow()
                this.getNotificationPopup()
                this.getItemStock()
            }.bind(this), 5000);

        },
        setNotif(){
            if (this.show==0){
               this.show=1
            }else{
               this.show=0
            }
        },
        setNotificationType(type){
            if(type=="reminder"){
                this.notification_icon="fas fa-stopwatch"
                this.notification_bagde="badge badge-warning navbar-badge"
                this.notification_title="Reminders"
            }
        }
    }
}
</script>

