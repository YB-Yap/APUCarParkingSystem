<template>
    <div class="page">
        <div class="page-header">
            <h1 class="page-title">
                <span class="mdi mdi-parking"></span>Car Park Status
                </h1>
        </div>
        <div class="page-content">
            {{ parking_availability }}
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                parking_availability: 0,
            }
        },
        created() {
            axios.get('/api/availability/carpark')
            .then((result) => {
                this.parking_availability = result.data;
            })
        },
    }
</script>

<style lang="scss">
    @import './resources/sass/_variables.scss';

</style>
