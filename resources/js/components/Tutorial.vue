<template>

</template>

<script>
    function initialState(){
        return {
        }
    }
    export default {
        data(){
            return initialState();
        },
        mounted() {
            if (!this.$cookies.isKey('tutorial_skip')) {
                this.displayTutorial();
            }
        },
        methods:{
            displayTutorial(){
                this.$swal.fire({
                    title:'Using iSupport',
                    html:`
                        <div class="card-body text-left">
                            <p>1. Click on the services button.</p>
                            <p>2. Click on the service you want to avail from the side bar that pops up.</p>
                            <p>3. Fill up the form for the service.</p>
                            <p>4. A token will be given after you click submit. A copy will be sent to your provided email.</p>
                            <p>5. Click on the login button.</p>
                            <p>6. Type in the token that was provided earlier to track your availed service.</p>
                        </div>`,
                    input: 'checkbox',
                    inputValue: 0,
                    inputPlaceholder: "Don't show again!",
                }).then(result=>{
                    if (result.value) {
                        this.$cookies.set('tutorial_skip', 1, Infinity);
                    }
                })
            }
        }
    }
</script>
