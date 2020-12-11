<template>
    <div class="card" style="height: 10rem">
        <div class="card-header">Recently Viewed</div>
        <div class="card-body">
            <a href="#">{{ inquiry != null ? inquiry.inquiry.token:'No Tickets have been updated'}}</a>
        </div>
    </div>
</template>

<script>
    function initialState(){
        return {
            inquiry:null,
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
                axios.post('/staff/recent', {id:this.user.id})
                .then(response=>{
                    if (response.data.length > 0) {
                        this.inquiry = response.data;
                    }
                })
                .catch(e=>{
                    console.log(e);
                })
            },
        }
    }
</script>
