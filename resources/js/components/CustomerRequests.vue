<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Tickets</h4>
                        <b-table 
                            responsive 
                            :items="inquiries" 
                            :fields="fields"
                            :per-page="pagi.perPage"
                            :current-page="pagi.currentPage"
                            :busy="pagi.busy"
                            hover 
                            show-empty 
                            empty-text="No customer requests"
                        >
                            <template v-slot:table-busy>
                                <div class="text-center my-2">
                                    <b-spinner class="align-middle"></b-spinner>
                                    <strong>Loading...</strong>
                                </div>
                            </template>
                            <template v-slot:cell(staff)="row">
                                {{row.item.assigned_staff == null ? 'No staff assigned':row.item.staff.name}}
                            </template>
                            <template v-slot:cell(actions)="row">
                                <b-dropdown split variant="outline-success" split-variant="success" class="m-2">
                                    <template v-slot:button-content>
                                        <i class="fa fa-cog"></i>
                                    </template>
                                    <b-dropdown-group header="Extra Details">
                                        <b-dropdown-item-button @click="row.toggleDetails">
                                            Customer Details
                                        </b-dropdown-item-button>
                                        <b-dropdown-item-button @click="showRemarks(row.item)">
                                            Remarks
                                        </b-dropdown-item-button>
                                        <b-dropdown-item-button @click="showIncidents(row.item)">
                                            {{row.item.incident.length != 0 ? row.item.incident.length:''}} Attachments
                                        </b-dropdown-item-button>
                                    </b-dropdown-group>
                                    <b-dropdown-group header="Actions">
                                        <b-dropdown-item-button v-if="row.item.payment_status == 'No Payment'" @click="confirmPayment(row.item)">
                                            Confirm Payment
                                        </b-dropdown-item-button>
                                        <b-dropdown-item-button @click="changeStatus(row.item)">
                                            Change Status
                                        </b-dropdown-item-button>
                                        <b-dropdown-item-button @click="resendMail(row.item)">
                                            Resend E-mail
                                        </b-dropdown-item-button>
                                        <b-dropdown-item-button @click="deleteRequest(row.item)">
                                            Delete Request
                                        </b-dropdown-item-button>
                                    </b-dropdown-group>
                                    <b-dropdown-group header="Payment">
                                        <b-dropdown-item-button @click="confirmPayment(row.item)">
                                            Initial Payment
                                        </b-dropdown-item-button>
                                        <b-dropdown-item-button @click="confirmPayment(row.item)">
                                            Full Payment
                                        </b-dropdown-item-button>
                                    </b-dropdown-group>
                                </b-dropdown>
                            </template>
                            <template v-slot:row-details="row">
                                <div v-if="row.item.customer != null" class="card">
                                    <div class="card-body row">
                                        <div class="col-sm-4 row">
                                                Customer: <br>{{ row.item.customer.name }}<br>
                                                Email: <br>{{ row.item.customer.email }}<br>
                                                Mobile No.: <br>{{ row.item.customer.mobile }}
                                        </div>
                                        <div class="col-sm-4">
                                            Address: <br>{{ row.item.customer.address }}<br>
                                            Payment Method: <br>{{ row.item.customer.payment_method }}
                                        </div>
                                        <div class="col-sm-4">
                                            Company: <br>{{ row.item.customer.company }}<br>
                                            Company Email: <br>{{row.item.customer.coemail }}<br>
                                            Company Number: <br>{{row.item.customer.comobile }}
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </b-table>
                        <div class="d-flex justify-content-center">
                            <b-pagination v-model="pagi.currentPage" :total-rows="inquirylength" :per-page="pagi.perPage"></b-pagination>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <b-modal id="remarks" size="lg" title="Remarks" hide-footer>
            <div class="container">
                <b-table 
                    responsive 
                    show-empty 
                    empty-text="No Remarks have been added"
                    :items="remarkinfo.table"
                    :fields="remarkinfo.fields"
                >
                </b-table>
            </div>
        </b-modal>
        <b-modal id="incidents" size="lg" title="Incident Reports" hide-footer>
            <b-table 
                responsive 
                show-empty 
                empty-text="No incident reports"
                :items="incidentInfo.incidents"
                :fields="incidentInfo.fields"
            >
                <template v-slot:row-details="row">
                    <div v-for="attached in row.item.attachment">
                        <img v-if="attached.file_type.includes('image')" :src="'/storage/incidents/'+row.item.inquiry.token+'/'+attached.file_name" class="img-fluid">
                        <iframe v-if="attached.file_type.includes('pdf')" :src="'/storage/incidents/'+row.item.inquiry.token+'/'+attached.file_name"></iframe>
                        <a :href="'/storage/incidents/'+row.item.inquiry.token+'/'+attached.file_name">{{attached.file_name}}</a>
                    </div>
                </template>
                <template v-slot:cell(actions)="row">
                    <b-button pill variant="outline-success" @click="row.toggleDetails">
                        View Attachments
                    </b-button>
                </template>
            </b-table>
        </b-modal>
    </div>
</template>

<script>
    function initialState(){
        return {
            inquiries: [],
            pagi:{
                currentPage:1,
                perPage:8,
                busy:true
            },
            remarkinfo:{
                table:[],
                fields:[
                {key:'remark', label:'Remark'},
                {key:'staff.name', label:'Staff'},
                {key:'timestamp', label:'Submitted'}]
            },
            incidentInfo:{
                incidents:[],
                fields:[
                {key:'timestamp', label:'Time of Incident', sortable:true},
                {key:'report', label:'Incident'},
                {key:'actions', label:''}]
            },
            fields:[
            {key:'timestamp', label:'Date Requested', sortable:true},
            {key:'token', label:'Ticket Number'},
            {key:'project_name', label:'Service', sortable:true},
            {key:'customer.name', label:'Customer'},
            {key:'customer.company', label:'Company'},
            {key:'poi', label:'POI'},
            {key:'headend', label:'Head End'},
            {key:'status', label:'Status', sortable:true},
            {key:'payment_status', label:'Payment Status', sortable:true},
            {key:'staff', label:'Assigned'},
            {key:'expiration_date', label:'Expiration'},
            {key:'actions', label:''}],
            staff:{},
        }
    }
    export default {
        props:['user'],
        data(){
            return initialState();
        },
        created() {
            this.$bus.on('staff', (data) => {
                this.staff = data;
            })
        },
        mounted() {
            this.getInquiries();
            this.getAvailableStaff();
        },
        computed:{
            inquirylength(){
                return this.inquiries.length
            }
        },
        methods:{
            getInquiries(){
                axios.get('/inquiries')
                .then(response=>{
                    if (response.data.length > 0) {
                        this.inquiries = response.data;
                    }
                    this.pagi.busy = false;
                })
                .catch(e=>{
                    console.log(e);
                })
            },
            getAvailableStaff(){
                axios.get('/staff')
                .then(response=>{
                    if (response.data.length > 0) {
                        var staffselect = {};
                        var stafflist = response.data;
                        stafflist.forEach(function(staff) {
                            staffselect[staff.id] = staff.name;
                        });
                        this.staff = staffselect;
                    }
                })
            },
            confirmPayment(inquiry){
                this.$swal.fire({
                    title:'Please confirm payment before proceeding!',
                    icon:'warning',
                    showCancelButton: true
                }).then((result)=>{
                    if (result.value) {
                        axios.post('/inquiry/paid', {
                            inquiry: inquiry
                        }).then(response=>{
                            if (response.data == 'success') {
                                this.$swal.fire('Success', 'Payment has been confirmed', 'success')
                                this.getInquiries()
                            }
                        })
                    }
                })
            },
            changeStatus(inquiry){
                this.$swal.fire({
                    input:'select',
                    inputPlaceholder:'Select Status',
                    inputOptions:{
                        'On-going':'On-going',
                        'Done':'Done'
                    }
                }).then((result)=>{
                    if (result.value) {
                        axios.post('/inquiry/status', {
                            inquiry: inquiry,
                            status: result.value
                        }).then(response=>{
                            if (response.data == 'success') {
                                this.$swal.fire('Success', 'Inquiry has been changed status', 'success')
                                this.getInquiries()
                            }
                        })
                    }
                })
            },
            addRemarks(inquiry){
                this.$swal.fire({
                    input: 'text',
                    inputPlaceholder: 'Enter any remarks here',
                    showCancelButton: true
                }).then((result)=>{
                    if (result.value) {
                        axios.post('/inquiry/remarks', {
                            inquiry: inquiry,
                            staff: this.user,
                            remark: result.value
                        }).then(response=>{
                            if (response.data == 'success') {
                                this.$swal.fire('Success', 'Remarks have been updated', 'success')
                                this.getInquiries()
                            }
                        })
                    }
                })
            },
            showRemarks(item){
                this.remarkinfo.table = item.remarks
                this.$bvModal.show('remarks')
            },
            showIncidents(item){
                this.incidentInfo.incidents = item.incident
                this.$bvModal.show('incidents')
            },
            deleteRequest(item){
                this.$swal.fire({
                    title:"Are you sure?",
                    text:"This action can't be undone!",
                    icon:"warning",
                    showCancelButton:true
                }).then((result)=>{
                    if (result.value) {
                        axios.post('/inquiry/delete', {
                            inquiry: item
                        }).then(response=>{
                            if (response.data == 'success') {
                                this.$swal.fire('Success', 'Ticket has been deleted.', 'success');
                                this.getInquiries();
                            }else{
                                this.errorMessage();
                            }
                        })
                    }
                }).catch(e=>{
                    console.log(e);
                })
            },
            resendMail(ticket){
                axios.post('/inquiry/resend', {
                    email: ticket.customer.email,
                    token: ticket.token
                }).then(response=>{
                    if (response.data == 'email sent') {
                        this.$swal.fire('Success', 'E-mail has been sent to '+ticket.customer.email+'.', 'success');
                    }else{
                        this.errorMessage();
                    }
                }).catch(e=>{
                    console.log(e);
                })
            },
            errorMessage(){
                this.$swal.fire('Error', 'Please contact technical support!', 'error');
            }
        }
    }
</script>
