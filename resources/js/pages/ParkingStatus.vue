<template>
    <div class="page">
        <div class="page-header">
            <h1 class="page-title">Parking Status</h1>
        </div>
        <div class="page-content">
            <h1>Your vehicle</h1>
            <div v-if="is_in_parking">
                <span>Your car is currently parked in Zone {{ car_state.parking_zone }}.</span><br>
                <span>Enter time: {{ car_state.time_in }}</span><br>
                <span>Estimated parking fee: RM{{ estimated_fee }}</span>
            </div>
            <div v-else>
                <span>Your car is not parked in any Zone.</span>
            </div>

            <h1>Car Park</h1>
            Availability: {{ parking_availability }} of {{ parking_size }}
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                is_in_parking: false,
                car_state: {},
                estimated_fee: 0,
                parking_availability: 0,
                parking_size: 0,
            }
        },
        mounted() {
            this.getCarState();
            this.getCarParkAvailability();
            this.getCarParkSize();
        },
        methods: {
            getCarState() {
                axios
                    .get('/parking/get-state')
                    .then((result) => {
                        console.log(result.data)
                        if (result.data.isInParking) {
                            this.is_in_parking = true;
                            this.car_state = result.data.data;
                        }
                    });
            },
            getCarParkAvailability() {
                axios
                    .get('/api/parking/availability')
                    .then((result) => {
                        this.parking_availability = result.data;
                    });
            },
            getCarParkSize() {
                axios
                    .get('/api/parking/size')
                    .then((result) => {
                        this.parking_size = result.data;
                    });
            },
        }
    }
</script>

<style lang="scss">
    @import './resources/sass/_variables.scss';

</style>
