<template>
    <div class="page">
        <div class="page-header">
            <h1 class="page-title">Simulator</h1>
        </div>
        <div class="page-content">
            <div class="center-container">
                <h1>Parking gate</h1>
                <div class="section-wrapper">
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
                    <div class="d-flex justify-content-between">
                        <button
                            class="btn btn-success"
                            :disabled="is_in_parking || selected_parking_zone == ''"
                            @click="enterCarPark()"
                        >Enter Car Park</button>
                        <button class="btn btn-danger" :disabled="!is_in_parking" @click="exitCarPark()">Exit Car Park</button>
                    </div>
                </div>

                <h1>APCard</h1>
                <div class="section-wrapper">
                    <span>Your APCard currently has RM{{ apcard_balance }}.</span>
                </div>

                <h3>Topup</h3>
                <div class="section-wrapper">
                    <span>Choose how much you want to topup</span>
                    <div class="d-flex flex-wrap flex-row mt-2">
                        <button class="btn btn-primary topup-btn" @click="topup(500)">RM5.00</button>
                        <button class="btn btn-primary topup-btn" @click="topup(1000)">RM10.00</button>
                        <button class="btn btn-primary topup-btn" @click="topup(5000)">RM50.00</button>
                        <button class="btn btn-primary topup-btn" @click="topup(10000)">RM100.00</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                apcard_balance: 0,
                topup_amount: 0,
                invalid_topup: false,
                is_in_parking: false,
                car_state: {},
                selected_parking_zone: '',
            }
        },
        mounted() {
            this.getAPCardBalance();
            this.getCarState();
        },
        methods: {
            getAPCardBalance() {
                axios
                    .get('/apcard/balance')
                    .then((result) => {
                        this.apcard_balance = (result.data / 100).toFixed(2);
                    });
            },
            getCarState() {
                axios
                    .get('/parking/state')
                    .then((result) => {
                        if (result.data.isInParking) {
                            this.is_in_parking = true;
                            this.car_state = result.data.data[0];
                            this.selected_parking_zone = this.car_state.parking_zone;
                        }
                    });
            },
            enterCarPark() {
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
                            .post('/parking/enter', {
                                parking_zone: this.selected_parking_zone
                            })
                            .then((res) => {
                                if (res.status == 200) {
                                    this.getCarState();
                                    this.$forceUpdate();
                                    var msg = (this.car_state.is_car_park_full == 1)
                                        ? 'The car park is full. You will not be charged if you exit within 15 minutes.'
                                        : `You have entered Parking Zone ${this.selected_parking_zone}.`;
                                    this.$swal.fire({
                                        title: 'Welcome',
                                        text: msg,
                                        icon: 'info',
                                        confirmButtonText: 'OK'
                                    })
                                }
                            })
                    }
                })
            },
            exitCarPark() {
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
                            .post('/parking/exit')
                            .then((res) => {
                                if (res.data.isSuccess) {
                                    this.is_in_parking = false;
                                    this.getAPCardBalance();
                                    this.$forceUpdate();
                                    this.$swal.fire({
                                        title: 'See you next time',
                                        icon: 'success',
                                    })
                                } else {
                                    this.$swal.fire({
                                        title: res.data.message,
                                        text: `Please top up your APCard at least RM${(res.data.to_pay / 100).toFixed(2)}`,
                                        icon: 'error',
                                        confirmButtonText: 'Ok'
                                    })
                                }
                            })
                    }
                })
            },
            topup(_amount) {
                this.$swal({
                    title: 'Topup',
                    text: `Please wait while the system is adding RM${(_amount/100).toFixed(2)} to your APCard`,
                    icon: 'info',
                    allowOutsideClick: false,
                })
                this.$swal.showLoading();
                axios
                    .post('/apcard/topup', {amount: _amount})
                    .then((res) => {
                        if (res.status == 200) {
                            this.$swal.fire({
                                title: 'Topup',
                                text: 'Topup successful',
                                icon: 'success',
                            })
                            this.getAPCardBalance();
                            this.$forceUpdate();
                        }
                    })
            },
        }
    }
</script>

<style lang="scss">
    @import './resources/sass/_variables.scss';

    .topup-btn {
        flex-grow: 1;
        flex-shrink: 1;
        margin: 8px;
        padding: 10px 12px;
    }
</style>
