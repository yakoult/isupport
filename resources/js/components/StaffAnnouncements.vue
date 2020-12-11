<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Announcements</h3>
                        <b-table
                            responsive
                            hover
                            show-empty
                            empty-text="No announcements yet"
                            :items="notifs"
                            :fields="fields"
                        >
                            <template v-slot:cell(message)="row">
                                <a :href="row.item.message">{{row.item.message}}</a>
                            </template>
                        </b-table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    function initialState(){
        return {
            notifs:[],
            fields:[
            {key:'from.name', label:'From'},
            {key:'message', label:'Announcement'}]
        }
    }
    export default {
        props:['user'],
        data(){
            return initialState();
        },
        mounted() {
            this.$nextTick(function () {
                window.setInterval(() => {
                    this.getUserNotifs()
                }, 60000);
            })
        },
        computed:{
        },
        methods:{
            getUserNotifs(){
                axios.post('/notification/foruser', {id:this.user.id})
                .then(response=>{
                    this.notifs = response.data
                })
                .catch(e=>{
                    console.log(e)
                })
            }
        }
    }
</script>
