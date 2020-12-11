<template>
    <div class="container">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Agent List</h4>
                <div class="col-md-12 d-flex justify-content-end">
                    <b-button pill variant="outline-success" v-b-modal.staffForm>Create Staff Account</b-button>
                    <b-button pill variant="outline-success" @click="call">Invite All to Meeting</b-button>
                </div>
                <b-table 
                    class="mt-1" 
                    responsive 
                    :items="staff" 
                    :fields="fields"  
                    show-empty 
                    empty-text="No Staff created"
                >
                    <template v-slot:cell(actions)="row">
                        <b-button pill variant="success" @click="call(row.item)">
                            <i class="fa fa-phone"></i>
                        </b-button>
                        <b-button pill variant="warning" @click="editUser(row.item)">
                            <i class="fa fa-user-edit"></i>
                        </b-button>
                        <b-button pill variant="warning" @click="changePass(row.item)">
                            <i class="fa fa-key"></i>
                        </b-button>
                        <b-button pill variant="danger" @click="deleteUser(row.item)">
                            <i class="fa fa-user-times"></i>
                        </b-button>
                    </template>
                </b-table>
            </div>
        </div>
        <b-modal id="staffForm" @ok="creationHandle" size="lg" title="New Staff Form">
            <form ref="form" @submit.stop.prevent="createForm">
                <b-form-group label="Name">
                    <b-form-input 
                        id="name" 
                        v-model="newUser.name" 
                        placeholder="Enter staff name" 
                        :state="invalidName" 
                        required
                    ></b-form-input>
                </b-form-group>
                <b-form-group label="E-mail">
                    <b-form-input 
                        id="email" 
                        type="email" 
                        v-model="newUser.email" 
                        placeholder="Enter staff email" 
                        :state="invalidEmail"
                        required
                    ></b-form-input>
                </b-form-group>
                <b-form-group label="Password">
                    <b-form-input 
                        id="password" 
                        type="password" 
                        v-model="newUser.password" 
                        placeholder="Enter password" 
                        :state="invalidPass"
                        required
                    ></b-form-input>
                </b-form-group>
            </form>
        </b-modal>
        <b-modal id="editForm" @ok="editHandle" size="lg" title="Edit Staff">
            <form ref="updateForm" @submit.stop.prevent="editForm">
                <b-form-group label="Name">
                    <b-form-input 
                        id="name" 
                        v-model="updateStaff.name" 
                        :placeholder="selectedUser.name" 
                        :state="editName" 
                        required
                    ></b-form-input>
                </b-form-group>
                <b-form-group label="E-mail">
                    <b-form-input 
                        id="email" 
                        type="email" 
                        v-model="updateStaff.email" 
                        :placeholder="selectedUser.email" 
                        :state="editEmail"
                        required
                    ></b-form-input>
                </b-form-group>
            </form>
        </b-modal>
    </div>
</template>

<script>
    function initialState(){
        return {
            staff:[],
            fields:[
            {key:'name', label:'Staff'},
            {key:'email', label:'E-mail'},
            {key:'actions', label:''}],
            newUser:{
                name: null,
                email: null,
                password: null
            },
            selectedUser:{
                name:null,
                email:null,
            },
            updateStaff:{
                name: null,
                email: null,
            },
            notif:{
                message:'',
                passcode:'',
            }
        }
    }
    export default {
        props:['user'],
        data(){
            return initialState();
        },
        mounted() {
            this.getStaff()
        },
        computed: {
            invalidName(){
                if (this.newUser.name != null) {
                    return this.newUser.name.length >= 4 ? true:false
                }
            },
            invalidEmail(){
                if (this.newUser.email != null) {
                    return true
                }
            },
            invalidPass(){
                if (this.newUser.password != null) {
                    return true
                }
            },
            editName(){
                if (this.updateStaff.name != null) {
                    return this.updateStaff.name.length >= 4 ? true:false
                }
            },
            editEmail(){
                if (this.updateStaff.email != null) {
                    return this.updateStaff.email.length >= 4 ? true:false
                }
            },
            editPass(){
                if (this.selectedUser.password != null) {
                    return this.selectedUser.password.length >= 8 ? true:false
                }
            }
        },
        methods:{
            newStaff() {
                axios.post('/staff/new', this.newUser)
                .then(response=>{
                    if (response.data == 'success') {
                        this.$swal.fire('Staff Account Created', 'Account for '+this.newUser.name+' has been created.', 'success')
                        this.getStaff()
                        this.resetForm()
                    }
                })
                .catch(e=>{
                    console.log(e)
                })
            },
            editStaff() {
                if (this.updateStaff.name == null) {
                    this.updateStaff.name = this.selectedUser.name
                }
                if (this.updateStaff.email == null) {
                    this.updateStaff.name = this.selectedUser.email
                }
                this.updateStaff.id = this.selectedUser.id
                axios.post('/staff/update', this.updateStaff)
                .then(response=>{
                    if (response.data == 'success') {
                        this.$swal.fire('Updated', this.selectedUser.name+' has been updated', 'success')
                        this.getStaff()
                        this.resetForm()
                    }
                })
            },
            getStaff() {
                axios.get('/staff')
                .then(response=>{
                    if (response.data.length > 0) {
                        this.staff = response.data
                        this.$bus.emit('staff', this.forSelectInput(this.staff))
                    }
                })
                .catch(e=>{
                    console.log(e)
                })
            },
            resetForm() {
                this.newUser = { name: null, email: null, password: null}
                this.selectedUser = { name: null, email: null, password: null}
                this.updateStaff = { name: null, email: null, password: null}
            },
            creationHandle(bvModalEvt) {
                bvModalEvt.preventDefault()
                this.createForm()
            },
            staffCreationCheck() {
                const valid = this.$refs.form.checkValidity()
                return valid
            },
            createForm() {
                if (!this.staffCreationCheck()) {
                    return
                }
                this.newStaff()
                this.$nextTick(()=>{
                    this.$bvModal.hide('staffForm')
                })
            },
            editHandle(bvModalEvt) {
                bvModalEvt.preventDefault()
                this.editForm()
            },
            staffEditCheck() {
                const valid = this.$refs.updateForm.checkValidity()
                return valid
            },
            editForm() {
                if (!this.staffEditCheck()) {
                    return
                }
                this.editStaff()
                this.$nextTick(()=>{
                    this.$bvModal.hide('editForm')
                })
            },
            forSelectInput(staff){
                var objectarray = {};
                staff.forEach(function(entry) {
                    objectarray[entry.id] = entry.name;
                });
                return objectarray;
            },
            deleteUser(staff) {
                this.$swal.fire({
                    title:'Are you sure?',
                    text:"Deleting "+staff.name+" can not be reversed!",
                    icon:'warning',
                    showCancelButton: true
                }).then((result)=>{
                    if (result.value) {
                        axios.post('/staff/delete', {id: staff.id})
                        .then(response=>{
                            if (response.data == 'success') {
                                this.$swal.fire('Success', staff.name+"'s account has been deleted", 'success')
                                this.getStaff()
                            }
                        })
                        .catch(e=>{
                            console.log(e)
                        })
                    }
                })
            },
            editUser(staff) {
                this.selectedUser = staff;
                this.$bvModal.show('editForm');
            },
            changePass(staff){
                this.$swal.fire({
                    input:'password',
                    inputPlaceholder:'Enter password here',
                    showCancelButton:true
                }).then((result)=>{
                    if (result.value) {
                        axios.post('/staff/changePass', {id: staff.id, pass: result.value})
                        .then(response=>{
                            if (response.data == 'success') {
                                this.$swal.fire('Password Set', 'Password has been Changed', 'success')
                            }
                        })
                    }
                })
            },
            call(user){
                if (user.target) {
                    this.$swal.mixin({
                        showCancelButton:true,
                        confirmButtonText:'Next &rarr;',
                        progressSteps: ['1', '2']
                    })
                    .queue([
                        {
                            title:'Enter Meeting Link',
                            input:'text',
                            inputPlaceholder:'Link here',
                            preConfirm: (value) => {
                                this.notif.message = value
                            }
                        },
                        {
                            title:'Enter Pass Code',
                            text:'Leave empty if there is none',
                            input:'password',
                            preConfirm: (value) => {
                                this.notif.passcode = value
                            }
                        }
                    ])
                    .then((result)=>{
                        if (result.value) {
                            axios.post('/notification/new', {
                                to:'all',
                                userid:this.user.id,
                                notif: this.notif
                            }).then(response=>{
                                if (response.data == 'success') {
                                    this.$swal.fire('Success', 'All staff has been sent a notification!', 'success')
                                    this.notif = {message:'',passcode:''}
                                }
                            })
                        }
                    })
                }else{
                    this.$swal.mixin({
                        showCancelButton:true,
                        confirmButtonText:'Next &rarr;',
                        progressSteps: ['1', '2']
                    })
                    .queue([
                        {
                            title:'Enter Meeting Link',
                            input:'text',
                            inputPlaceholder:'Link here',
                            preConfirm: (value) => {
                                this.notif.message = value
                            }
                        },
                        {
                            title:'Enter Pass Code',
                            text:'Leave empty if there is none',
                            input:'password',
                            preConfirm: (value) => {
                                this.notif.passcode = value
                            }
                        }
                    ])
                    .then((result)=>{
                        if (result.value) {
                            axios.post('/notification/new', {
                                to: user.id,
                                userid:this.user.id,
                                notif: this.notif
                            }).then(response=>{
                                if (response.data == 'success') {
                                    this.$swal.fire('Success', 'All staff has been sent a notification!', 'success')
                                    this.notif = {message:'',passcode:''}
                                }
                            })
                        }
                    })
                }
            }
        }
    }
</script>
