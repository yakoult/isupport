<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="card-body">
                <h3 class="card-title">Announcement</h3>
                <b-form-group
                    label="Title"
                >
                    <b-form-input
                        v-model="announcement.title"
                        placeholder="Enter announcement title"
                    ></b-form-input>
                </b-form-group>
                <b-form-group
                    label="Announcement Message"
                >
                    <markdown-editor v-model="announcement.message"></markdown-editor>
                </b-form-group>
                <b-button pill variant="primary" @click="postAnnouncement">Post Announcement</b-button>
                <b-button pill variant="success" v-b-modal.preview>Preview</b-button>
            </div>
        </div>
        <b-modal id="preview" size="lg" hide-footer hide-header>
            <div class="card-body">
                <div>
                    <h2>{{announcement.title}}</h2>
                </div>
                <div v-html="previewText"></div>
            </div>
        </b-modal>
    </div>
</template>

<script>
    import Marked from 'marked';
    function initialState(){
        return {
            announcement:{
                message:'',
                title:''
            }
        }
    }
    export default {
        data(){
            return initialState();
        },
        mounted() {
        },
        computed: {
            previewText(){
                return Marked(this.announcement.message, {breaks:true});
            }
        },
        methods:{
            postAnnouncement(){
                axios.post('/admin/announcement', this.announcement)
                .then(response=>{
                    if (response.data == 'success') {
                        this.$swal.fire('Success', 'Announcement Changed', 'success')
                    }
                })
            }
        }
    }
</script>
