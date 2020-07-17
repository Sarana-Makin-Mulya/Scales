/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

// Veevalidate
import id from 'vee-validate/dist/locale/id';
import * as rules from 'vee-validate/dist/rules';
import { ValidationObserver, ValidationProvider, extend } from 'vee-validate';

for (let rule in rules) {
  extend(rule, {
    ...rules[rule],
    message: id.messages[rule]
  });
}

import "vue-swatches/dist/vue-swatches.min.css"
import 'vue2-datepicker/index.css';

import JsonExcel from 'vue-json-excel'
Vue.component('downloadExcel', JsonExcel);
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */
// Vue.component('example-component', require('./components/ExampleComponent.vue').default);

// Veevalidate
Vue.component('validation-observer', ValidationObserver);
Vue.component('validation-provider', ValidationProvider);

// Weighing
Vue.component('weighing-main', require('./pages/warehouses/weighing/WeighingMain.vue').default);
Vue.component('weighing-table-records', require('./pages/warehouses/weighing/Weighing.vue').default);
Vue.component('weighing-category-table-records', require('./pages/warehouses/weighing/WeighingCategory.vue').default);
Vue.component('weighing-item-table-records', require('./pages/warehouses/weighing/WeighingItem.vue').default);
Vue.component('weighing-detail', require('./pages/warehouses/weighing/WeighingDetail.vue').default);

const app = new Vue({
    el: '#app',
});
