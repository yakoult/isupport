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
                            hover 
                            show-empty 
                            empty-text="No customer requests assigned to you"
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
                                    <b-dropdown-item-button @click="row.toggleDetails">
                                        View Remarks
                                    </b-dropdown-item-button>
                                    <b-dropdown-item-button v-b-modal.incidentForm @click="selectRow(row.item)">
                                        File Incident Report
                                    </b-dropdown-item-button>
                                    <b-dropdown-item-button @click="addRemarks(row.item)">
                                        Update Remarks
                                    </b-dropdown-item-button>
                                    <b-dropdown-item-button @click="changeStatus(row.item)">
                                        Change Status
                                    </b-dropdown-item-button>
                                </b-dropdown>
                            </template>
                            <template v-slot:row-details="row">
                                <b-table
                                    responsive
                                    borderless
                                    table-variant="secondary"
                                    show-empty
                                    empty-test="No Remarks from you have been made"
                                    :items="row.item.remarks"
                                    :fields="remarkfield"
                                ></b-table>
                            </template>
                        </b-table>
                    </div>
                </div>
            </div>
        </div>
        <b-modal id="incidentForm" title="Incident Report Form" @ok="okHandle" size="lg">
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
            form:{
                description:'',
                ticket:null,
                file:[]
            },
            filter: null,
            filterOn: [],
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
            }
        },
        methods:{
            getInquiries(){
                axios.post('/inquiry/forStaff', {user:this.user})
                .then(response=>{
                    if (response.data.length > 0) {
                        this.inquiries = response.data;
                    }
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
            // showRemarks(row){
            //     if (row._showDetails) {
            //         this.$set(row, '_showDetails', false);
            //     }else{
            //         this.$set(row, '_showDetails', true);
            //     }
            // },
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
                    console.log(response.data)
                    this.resetForm()
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
