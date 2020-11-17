<template>
    <div class="page">
        <div class="page-header">
            <h1 class="page-title">Parking Status</h1>
        </div>
        <div class="page-content">
            <div class="center-container">
                <h1>Your vehicle</h1>
                <div class="section-wrapper">
                    <div v-if="is_in_parking">
                        <span>Your car is currently parked in Zone {{ car_state.parking_zone }}.</span><br>
                        <span>Enter time: {{ car_state.time_in }}</span><br>
                        <span>Estimated parking fee: RM{{ estimated_fee }}</span>
                    </div>
                    <div v-else>
                        <span>Your car is not parked in any Zone.</span><br>
                        Zone A: {{ parking_availability.zone_a }} of {{ parking_size.zone_a }}<br>
                        Zone B: {{ parking_availability.zone_b }} of {{ parking_size.zone_b }}
                    </div>
                </div>

                <div v-if="has_parked_today" class="section-wrapper">
                    <span class="mdi mdi-parking"></span> Previously, you have parked at ...<br>
                    <span>Parking Zone: {{ latest_record.parking_zone }}</span><br>
                    <span>Enter time: {{ latest_record.time_in }}</span><br>
                    <span>Exit time: {{ latest_record.time_out }}</span><br>
                    <span>Duration: {{ `${latest_record.hours} hour(s) ${latest_record.minutes} minute(s)` }}</span><br>
                    <span>Parking fee: RM{{ (latest_record.fee / 100).toFixed(2) }}</span>
                </div>
            </div>
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
                parking_availability: [],
                parking_size: [],
                has_parked_today: false,
                latest_record: {
                    hours: 0,
                    minutes: 0,
                },
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
                            this.car_state = result.data.data[0];
                            this.estimateParkingFee();
                        }
                        if (result.data.hasParkedToday) {
                            this.has_parked_today = true;
                            this.latest_record = result.data.data[1];
                            this.latest_record.hours = Math.floor(this.latest_record.duration);
                            let minutes = (this.latest_record.duration - this.latest_record.hours) * 60;
                            this.latest_record.minutes = Math.floor(minutes);
                        }
                    });
            },
            estimateParkingFee() {
                axios
                    .get('/parking/estimate-fee')
                    .then((result) => {
                        console.log(result.data)
                        this.estimated_fee = (result.data / 100).toFixed(2);
                        this.$forceUpdate();
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
