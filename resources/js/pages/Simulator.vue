<template>
    <div class="page">
        <div class="page-header">
            <h1 class="page-title">Simulator</h1>
        </div>
        <div class="page-content">
            <h1>Parking gate</h1>
            <span v-if="is_in_parking">Your car is currently parked in Zone {{ car_state.parking_zone }}.</span>
            <span v-else>Your car is not parked in any Zone.</span>

            <div class="form-group">
                <label for="parkingZone-select">Please select one parking zone to enter.</label>
                <select
                    v-model="selected_parking_zone"
                    class="form-control"
                    id="parkingZone-select"
                    :disabled="is_in_parking"
                >
                    <option value="A">A</option>
                    <option value="B">B</option>
                </select>
            </div>

            <button class="btn btn-success" :disabled="is_in_parking" @click="enterCarPark()">Enter Car Park</button>
            <button class="btn btn-danger" :disabled="!is_in_parking" @click="exitCarPark()">Exit Car Park</button>

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
                apcard_balance: 0,
                is_in_parking: false,
                car_state: {},
                selected_parking_zone: '',
            }
        },
        mounted() {
            axios
                .get('/apcard-balance')
                .then((result) => {
                    this.apcard_balance = (result.data / 100).toFixed(2);
                });
            this.getCarState();
        },
        methods: {
            getCarState() {
                axios
                    .get('/get-car-state')
                    .then((result) => {
                        console.log(result.data)
                        if (result.data.isInParking) {
                            this.is_in_parking = true;
                            this.car_state = result.data.data;
                            this.selected_parking_zone = this.car_state.parking_zone;
                        }
                    });
            },
            enterCarPark() {
                console.log(`entering ${this.selected_parking_zone}`);
                this.$swal.fire({
                    title: 'Entering Car Park',
                    text: `You are about to enter Parking Zone ${this.selected_parking_zone}?`,
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonText: 'Enter',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.value) {
                        axios
                            .post('/carpark/enter', {
                                parking_zone: this.selected_parking_zone
                            })
                            .then((res) => {
                                if (res.status == 200) {
                                    this.getCarState();
                                    this.$forceUpdate();
                                }
                            })
                    }
                })
            },
            exitCarPark() {
                console.log(`exiting ${this.selected_parking_zone}`);
                this.$swal.fire({
                    title: 'Exiting Car Park',
                    text: `You are about to exit Parking Zone ${this.selected_parking_zone}?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Exit',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.value) {
                        axios
                            .post('/carpark/exit')
                            .then((res) => {
                                if (res.status == 200) {
                                    this.is_in_parking = false;
                                    this.$forceUpdate();
                                }
                            })
                    }
                })
            }
        }

    }
</script>

<style lang="scss">
    @import './resources/sass/_variables.scss';

</style>
