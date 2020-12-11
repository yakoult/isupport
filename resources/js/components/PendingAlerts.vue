<template>
    <div class="card" style="height: 20rem">
        <div class="card-header">Alerts and Pending Tasks</div>
        <div class="card-body">
            <div v-for="inquiry in inquiries">
                <i class="fa fa-flag text-danger mr-3"></i><a href="#">{{inquiry.token}}</a>
            </div>
        </div>
    </div>
</template>

<script>
    function initialState(){
        return {
            inquiries: [],
        }
    }
    export default {
        props:['user'],
        data(){
            return initialState();
        },
        mounted() {
            this.getPending();
        },
        methods:{
            getPending(){
                axios.post('/staff/pending', {id:this.user.id})
                .then(response=>{
                    if (response.data.length > 0) {
                        this.inquiries = response.data;
                    }
                })
                .catch(e=>{
                    console.log(e);
                })
            },
        }
    }
</script>
