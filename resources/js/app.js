/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
window.Vue = require('vue');

//Popperjs
import Popper from 'vue-popperjs';
import 'vue-popperjs/dist/vue-popper.css';

//vue-event-bus
import VueBus from 'vue-bus';
Vue.use(VueBus);

//boostrap vue
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue';
Vue.use(BootstrapVue);
Vue.use(IconsPlugin);

//waypoint vue
import VueWaypoint from 'vue-waypoint';
Vue.use(VueWaypoint);

//Swal2 vue
import VueSweetalert2 from 'vue-sweetalert2';
Vue.use(VueSweetalert2);

//Markdown Editor
import 'v-markdown-editor/dist/v-markdown-editor.css';
import Editor from 'v-markdown-editor';
Vue.use(Editor);

//Cookies
import VueCookies from 'vue-cookies';
Vue.use(VueCookies);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('customer-requests', require('./components/CustomerRequests.vue').default);
Vue.component('request-lookup', require('./components/InquiryLookup.vue').default);
Vue.component('product-list', require('./components/ProductList.vue').default);
Vue.component('staff-creation', require('./components/StaffManagement.vue').default);
Vue.component('staff-dashboard', require('./components/StaffDashboard.vue').default);
Vue.component('staff-ticketlist', require('./components/StaffTicketList.vue').default);
Vue.component('staff-announcements', require('./components/StaffAnnouncements.vue').default);
Vue.component('announcements', require('./components/Announcements.vue').default);
Vue.component('announcement-form', require('./components/AnnouncementForm.vue').default);
Vue.component('tutorial-popup', require('./components/Tutorial.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
 
const app = new Vue({
    el: '#app',
    components: {
    	'popper': Popper
    },
    data: {
        announcement:null,
    	screenwidth: null,
    	screenheight: null,
    	fixed: false,
        toggle: '',
        isMobile:false,
    	intersectionOptions: {
		    root: null,
		    rootMargin: '0px 0px 0px 0px',
		    threshold: [0, 1] // [0.25, 0.75] if you want a 25% offset!
	    }
    },
    created(){
    	this.getScreensize();
        this.getAnnouncement();
        if (navigator.userAgent.match(/Android/i) 
            || navigator.userAgent.match(/webOS/i) 
            || navigator.userAgent.match(/iPhone/i)  
            || navigator.userAgent.match(/iPad/i)  
            || navigator.userAgent.match(/iPod/i) 
            || navigator.userAgent.match(/BlackBerry/i) 
            || navigator.userAgent.match(/Windows Phone/i)) { 
            this.isMobile = true; 
        } else { 
            this.isMobile = false; 
        }
    },
    mounted(){
        var url = window.location.href.split('/').pop()
        if (url == '?ticket=on') {
            this.$bvModal.show('loginmodal')
        }else if(url.includes('?sent=')){
            let ticket = url.split('=').pop();
            axios.post('/api/ticket', {
            	token: ticket,
            }).then(response=>{
            	let date = new Date(response.data.expiration_date);
            	this.$swal.fire({
            	    title:'Your ticket has been submitted!',
            	    html:`
            	        <p>Your ticket number is: <code>${ticket}</code></p>
            	        <p>This will expire on: <code>${date}</code></p>
            	        <p>A copy of your ticket number has been sent to your email. You will also receive mail about updates regarding your availed service.</p>    
            	        <p>Ex. Payment Confirmation, Incidents, Service Completion and Ticket Expiration.</p>`
            	}).then((result)=>{
            		if (result.isConfirmed) {
            			window.location = "http://bobsupportgroup.com";
            		}
            	})
            }).catch(e=>{
            	console.log(e)
            });
        }else{
            if (this.announcement != null) {
                this.$bvModal.show('announcement')
            }
        }
    },
    methods: {
    	getScreensize()
    	{
    		this.screenheight = $(window).height();
    		this.screenwidth = $(window).width();
    	},
    	navbarPoint({ going, direction }) {
    		if (going === this.$waypointMap.GOING_OUT && direction == 'top') {
                this.fixed = true;
    		}
    	},
        jumboPoint({ going, direction }) {
            if (going === this.$waypointMap.GOING_IN && this.fixed == true) {
                this.fixed = false;
            }
        },
        toggleSidebar(event)
        {
            if (this.toggle == '') {
                this.toggle = 'active'
            }else{
                this.toggle = ''
            }
        },
        getAnnouncement(){
            axios.get('/api/announcement')
            .then(response=>{
                this.announcement = response.data
            })
        }
    }
});
