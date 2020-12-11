<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="card-body">
                <input class="form-control" type="text" v-model="token" placeholder="Enter Token here">
            </div>
        </div>
        <div v-if="token != '' && inquiry != ''" class="row">
            <div class="col-md-12">
                <h5>Service</h5><br>
            </div>
            <div class="col-12 col-md-4">
                <p><b>Project:</b></p>
                <p>{{inquiry.project_name}}</p>
                <p><b>POI:</b></p> 
                <p>{{inquiry.poi}}</p>
                <p><b>Head End:</b></p>
                <p>{{inquiry.headend}}</p>
            </div>
            <div class="col-12 col-md-4">
                <p><b>Status:</b></p>
                <p>{{inquiry.status}}</p>
                <p><b>Latest Update:</b></p>
                <p>{{inquiry.remarks.length > 0 ? inquiry.remarks[0].remark:'No remarks have been made'}}</p>
                <p><b>Expires By:</b></p>
                <p>{{inquiry.expiration_date}}</p>
            </div>
            <div class="col-12 col-md-4">
                <p><b>Payment Status:</b></p>
                <p>{{inquiry.payment_status}}</p>
            </div>
            <div class="col-12 col-md-12">
                <div class="d-flex justify-content-end">
                    <!-- <button v-if="inquiry.incident.length > 0" class="btn btn-primary" @click="downloadFiles()">Download attachments</button> -->
                    <button v-if="inquiry.remarks.length > 0" class="btn btn-success" @click="toggleHistory('remarks')">Update History</button>
                    <button v-if="inquiry.incident.length > 0" class="btn btn-success" @click="toggleHistory('report')">Incident History</button>
                    <button class="btn btn-danger" @click="generatePDF()">Download as PDF</button>
                </div>
            </div>
            <div v-if="toggle == 'remarks'" class="col-12 col-md-12 mt-1">
                <b-table responsive :items="inquiry.remarks" :fields="fields" show-empty empty-text="No updates have been made to this request"></b-table>
            </div>
            <div v-if="toggle == 'report'" class="col-12 col-md-12 mt-1">
                <b-table responsive :items="inquiry.incident" :fields="rfields" show-empty empty-text="No updates have been made to this request">
                    <template v-slot:cell(actions)="row">
                        <button class="btn btn-success" @click="row.toggleDetails">
                            Show Attachment
                        </button>
                    </template>
                    <template v-slot:row-details="row">
                        <div class="d-flex justify-content-center" v-for="attached in row.item.attachment">
                            <img v-if="attached.file_type.includes('image')" :src="'/storage/incidents/'+inquiry.token+'/'+attached.file_name" class="img-fluid">
                            <iframe v-else-if="attached.file_type.includes('pdf')" :src="'/storage/incidents/'+inquiry.token+'/'+attached.file_name" frameborder="0"></iframe>
                            <br>
                            <a :href="'/storage/incidents/'+inquiry.token+'/'+attached.file_name">Download File</a>
                        </div>
                    </template>
                </b-table>
            </div>
        </div>
        <div v-else-if="inquiry == '' && showContent" class="row">
            <h3>No matching token found</h3>
        </div>
    </div>
</template>

<script>
    function initialState(){
        return {
            inquiry: '',
            token: '',
            toggle: '',
            fields:[
            {key:'timestamp', label:'Time'},
            {key:'remark', label:'Update'}],
            rfields:[
            {key:'timestamp', label:'Time'},
            {key:'report', label:'Report'},
            {key:'actions', label:''}],
        }
    }
    export default {
        data(){
            return initialState();
        },
        mounted() {
        },
        computed: {
            showContent() {
                if (this.token != '') {
                    return this.token.length >= 4 ? true : false
                }
            }
        },
        methods:{
            getInquiry(){
                axios.post('/api/ticket', {
                    token: this.token
                }).then(response=>{
                    this.inquiry = response.data;
                }).catch(e=>{
                    console.log(e)
                })
            },
            downloadFiles(){
                var self = this;
                this.inquiry.incident[0].attachment.forEach(function(attachment){
                    var a = document.createElement('a');
                    a.href = '/storage/incidents/'+self.inquiry.token+'/'+attachment.file_name;
                    a.download = attachment.file_name;
                    document.body.appendChild(a);
                    a.click();
                    window.URL.revokeObjectURL(a.href);
                    a.remove();
                })
            },
            generatePDF(){
                var pdf = new jsPDF('p', 'pt', 'letter');
                // source can be HTML-formatted string, or a reference
                // to an actual DOM element from which the text will be scraped.
                source = $('#content')[0];

                // we support special element handlers. Register them with jQuery-style 
                // ID selector for either ID or node name. ("#iAmID", "div", "span" etc.)
                // There is no support for any other type of selectors 
                // (class, of compound) at this time.
                specialElementHandlers = {
                    // element with id of "bypass" - jQuery style selector
                    '#bypassme': function (element, renderer) {
                        // true = "handled elsewhere, bypass text extraction"
                        return true
                    }
                };
                margins = {
                    top: 80,
                    bottom: 60,
                    left: 40,
                    width: 522
                };
                // all coords and widths are in jsPDF instance's declared units
                // 'inches' in this case
                pdf.fromHTML(
                    source, // HTML string or DOM elem ref.
                    margins.left, // x coord
                    margins.top, { // y coord
                        'width': margins.width, // max width of content on PDF
                        'elementHandlers': specialElementHandlers
                    },

                    function (dispose) { 
                        // dispose: object with X, Y of the last line add to the PDF 
                        //          this allow the insertion of new lines after html
                        pdf.save('Test.pdf');
                    }, margins
                );
            },
            toggleHistory(item){
                this.toggle = item;
            }
        },
        watch:{
            token(){
                if (this.token.length >= 4) {
                    this.getInquiry();
                }
            }
        }
    }
</script>
