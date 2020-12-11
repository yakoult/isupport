<template>
    <div class="container">
        <div v-if="announcement != null">
            <h2>{{announcement.title}}</h2>
            <div v-html="message"></div>
        </div>
    </div>
</template>

<script>
    import Marked from 'marked';
    function initialState(){
        return {
            announcement:""
        }
    }
    export default {
        data(){
            return initialState();
        },
        mounted() {
            this.getAnnouncement();
        },
        computed: {
            message(){
                if (this.announcement != "") {
                    return Marked(this.announcement.message)
                }
            }
        },
        methods:{
            getAnnouncement(){
                axios.get('/api/announcement')
                .then(response=>{
                    this.announcement = response.data
                })
            }
        }
    }
</script>
