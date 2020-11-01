<template>
    <div class="page">
        <div class="page-header">
            <h1 class="page-title">Simulator</h1>
        </div>
        <div class="page-content">

            <h1>Parking gate</h1>


            <h1>APCard</h1>
            <span>Your APCard currently has RM{{ apcard_balance }}.</span>

            <h3>Topup</h3>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['user_id'],
        data() {
            return {
                apcard_balance: 0
            }
        },
        mounted() {
            axios.get('/apcard-balance')
            .then((result) => {
                this.apcard_balance = (result.data / 100).toFixed(2);
            })
        }

    }
</script>

<style lang="scss">
    @import './resources/sass/_variables.scss';

</style>
