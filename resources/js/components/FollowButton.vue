<template>
    <div>
        <button class="btn btn-primary ml-4" 
            @click="followUser"
            v-text="buttonText">
        </button>
    </div>
</template>

<script>
    export default {
        props: ['userId', 'follows'],                
        mounted() {
            console.log('Component mounted.')
        },
        data: function(){
            return {
                status: this.follows,
            }
        },
        methods:{
            followUser(){
                axios.post('/follow/' + this.userId)
                    .then(respose => {
                        this.status = ! this.status;
                    })
                    .catch(errors => {
                        switch(errors.response.status){
                            case 401:
                                window.location = '/login'
                                break;
                            default:                                
                                break;
                        }
                    });
            }
        },
        computed: {
            buttonText() {
                return (this.status) ? 'Unfollow':'Follow Me Now';
            }
        }
    }
</script>
