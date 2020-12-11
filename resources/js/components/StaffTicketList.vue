<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-6 col-md-2">
                <div class="card-deck">
                    <div class="card">
                        <div class="card-body">
                            <b-form-group
                                label="Filter"
                                label-cols-sm="3"
                                label-align-sm="right"
                                label-size="sm"
                                label-for="filterInput"
                                class="mb-0"
                            >
                                <b-form-input v-model="filter" type="search" placeholder="Type to Search">
                                    <b-input-group-append>
                                        <b-button :disabled="!filter" @click="filter = ''">Clear</b-button>
                                    </b-input-group-append>
                                </b-form-input>
                            </b-form-group>
                            <b-form-group
                                label="Filter On"
                                label-cols-sm="3"
                                label-align-sm="right"
                                label-size="sm"
                                description="Leave all unchecked to filter on all data"
                                class="mb-0"
                            >
                                <b-form-checkbox-group v-model="filterOn" class="mt-1">
                                    <b-form-checkbox value="project_name">Request</b-form-checkbox>
                                    <b-form-checkbox value="token">Token</b-form-checkbox>
                                    <b-form-checkbox value="poi">POI</b-form-checkbox>
                                    <b-form-checkbox value="headend">Head End</b-form-checkbox>
                                </b-form-checkbox-group>
                            </b-form-group>
                        </div>
                    </div>
                </div>
                <PendingAlerts :user="user"></PendingAlerts>
                <RecentlyViewed :user="user"></RecentlyViewed>
            </div>
            <div class="col-12 col-md-10">
                <div class="card">
                    <label class="card-header">Your Assigned Tasks</label>
                    <div class="card-body">
                        <b-table 
                            responsive 
                            :items="inquiries" 
                            :fields="fields" 
                            :filter="filter"
                            :filterIncludedFields="filterOn"
                            :per-page="table.perPage"
                            :current-page="table.currentPage"
                            :busy="busy" 
                            sm="stacked"
                            show-empty 
                            empty-text="No customer requests assigned to you"
                            class="min-35"
                        >
                            <template v-slot:cell(poi)="row">
                                {{row.item.poi == null ? 'N/A':row.item.poi}}
                            </template>
                            <template v-slot:cell(headend)="row">
                                {{row.item.headend == null ? 'N/A':row.item.headend}}
                            </template>
                            <template v-slot:cell(actions)="row">
                                <b-dropdown split variant="outline-success" split-variant="success" class="m-2">
                                    <template v-slot:button-content>
                                        <i class="fa fa-cog"></i>
                                    </template>
                                    <b-dropdown-group header="Extra Details">
                                        <b-dropdown-item-button @click="showExtra(row.item, 'customer')">
                                            View Customer Details
                                        </b-dropdown-item-button>
                                        <b-dropdown-item-button @click="showExtra(row.item, 'remarks')">
                                            View Remarks
                                        </b-dropdown-item-button>
                                        <b-dropdown-item-button @click="showExtra(row.item, 'incidents')">
                                            View Incidents
                                        </b-dropdown-item-button>
                                    </b-dropdown-group>
                                    <b-dropdown-group header="Actions">
                                        <b-dropdown-item-button v-b-modal.incidentForm @click="selectRow(row.item)">
                                            File Incident Report
                                        </b-dropdown-item-button>
                                        <b-dropdown-item-button @click="addRemarks(row.item)">
                                            Update Remarks
                                        </b-dropdown-item-button>
                                        <b-dropdown-item-button @click="changeStatus(row.item)">
                                            Change Status
                                        </b-dropdown-item-button>
                                    </b-dropdown-group>
                                </b-dropdown>
                            </template>
                            <template v-slot:row-details="row">
                                <div v-if="tableDetails == 'customer'" class="card">
                                    <div class="card-body d-flex">
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
                                <b-table
                                    v-if="tableDetails == 'remarks'"
                                    responsive
                                    borderless
                                    table-variant="secondary"
                                    show-empty
                                    empty-test="No Remarks from you have been made"
                                    :items="row.item.remarks"
                                    :fields="remarkfield"
                                ></b-table>
                                <b-table
                                    v-if="tableDetails == 'incidents'"
                                    responsive
                                    borderless
                                    table-variant="secondary"
                                    show-empty
                                    empty-test="No Incidents from you have been made"
                                    :items="row.item.incident"
                                    :fields="incidentfield"
                                ></b-table>
                            </template>
                        </b-table>
                        <div v-if="totalRows > 10" class="d-flex justify-content-center">
                            <b-pagination
                                v-model="table.currentPage"
                                :total-rows="totalRows"
                                :per-page="table.perPage"
                            ></b-pagination>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <b-modal id="incidentForm" title="Attach a File" @ok="okHandle" size="lg">
            <form ref="form" @submit.stop.prevent="formCheck">
                <b-form-group label="Description">
                    <b-form-input 
                        id="description" 
                        v-model="form.description" 
                        placeholder="Enter Report Here" 
                        :state="validEntry" 
                        required
                    ></b-form-input>
                </b-form-group>
                <b-form-group label="Attachment">
                    <b-form-file 
                        id="attachment" 
                        multiple
                        v-model="form.file" 
                        placeholder="Browse Files"
                        drop-placeholder="Drag and drop files here"
                    ></b-form-file>
                </b-form-group>
            </form>
        </b-modal>
    </div>
</template>

<script>
    import PendingAlerts from './PendingAlerts.vue'
    import RecentlyViewed from './RecentlyViewed.vue'
    function initialState(){
        return {
            inquiries: [],
            fields:[
            {key:'timestamp', label:'Date of Request', sortable:true},
            {key:'token', label:'Token'},
            {key:'project_name', label:'Service', sortable:true},
            {key:'poi', label:'POI'},
            {key:'headend', label:'Head End'},
            {key:'status', label:'Status', sortable:true},
            {key:'expiration_date', label:'Expiration', sortable:true},
            {key:'actions', label:''}],
            remarkfield:[
            {key:'timestamp', label:'Time Updated', sortable:true},
            {key:'remark', label:'Remark'}],
            incidentfield:[
            {key:'timestamp', label:'Date of Incident'},
            {key:'report', label:'Report'},
            {key:'actions', label: ''}],
            form:{
                description:'',
                ticket:null,
                file:[]
            },
            table:{
                perPage: 7,
                currentPage: 1,
            },
            busy:true,
            filter: null,
            filterOn: [],
            tableDetails:'',
        }
    }
    export default {
        props:['user'],
        components: { PendingAlerts, RecentlyViewed },
        data(){
            return initialState();
        },
        mounted() {
            this.getInquiries();
        },
        computed:{
            validEntry(){
                return this.form.description.length > 0 ? true:false
            },
            totalRows(){
                return this.inquiries.length;
            }
        },
        methods:{
            getInquiries(){
                axios.get('/inquiries')
                .then(response=>{
                    if (response.data.length > 0) {
                        this.inquiries = response.data;
                    }
                    this.busy = false
                })
                .catch(e=>{
                    console.log(e);
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
            selectRow(ticket){
                this.form.ticket = ticket
            },
            showExtra(row, type){
                if (row._showDetails) {
                    if (this.tableDetails == type) {
                        this.$set(row, '_showDetails', false);
                    }
                }else{
                    this.$set(row, '_showDetails', true);
                }
                this.tableDetails = type;
            },
            okHandle(bvModalEvt){
                bvModalEvt.preventDefault()
                this.checkForm()
            },
            checkForm(){
                if (!this.$refs.form.checkValidity()) {
                    return
                }
                this.fileReport()
                this.$nextTick(()=>{
                    this.$bvModal.hide('incidentForm')
                })
            },
            fileReport()
            {
                const data = new FormData()
                this.form.file.forEach((file,i)=>{
                    data.append('file['+i+']',file)
                });
                const json = JSON.stringify({
                    desc: this.form.description,
                    ticket: this.form.ticket,
                    staff: this.user
                })
                data.append('data', json)
                axios.post('/inquiry/incident', data, {headers:{'Content-Type':'multipart/form-data'}})
                .then(response=>{
                    if (response.data == 'success') {
                        this.$swal.fire('Success', 'Your report has been submitted', 'success')
                        this.getInquiries();
                    }
                })
                .catch(e=>{
                    console.log(e)
                })
            },
            resetForm(){
                this.form = {
                    description:'',
                    ticket:null,
                    file:[]
                }
            }
        }
    }
</script>
