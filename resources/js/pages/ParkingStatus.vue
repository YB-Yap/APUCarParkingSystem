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
                        <span class="d-block w-100 text-center">
                            Your car is currently parked in
                            <span class="parking-zone">Zone {{ car_state.parking_zone }}</span>.
                        </span>
                        <div class="d-flex flex-wrap justify-content-start mt-2">
                            <div class="parking-date text-center pt-2">
                                <span class="secondary-txt">Enter time:</span><br>
                                {{ car_state.time_in }}
                            </div>
                            <div class="parking-date text-center pt-2">
                                <span class="secondary-txt">Estimated parking fee:</span><br>
                                RM{{ estimated_fee }}
                            </div>
                        </div>
                    </div>
                    <div v-else>
                        <span class="d-block w-100 text-center mt-4">
                            Your car is not parked in any zone.
                        </span>
                    </div>
                    <hr>
                    <h5 class="mt-4 text-center section-title">
                        <span class="mdi mdi-parking"></span> Availability
                    </h5>
                    <div class="my-2">
                        <ParkingAvailabilityChart style="height: 150px;" />
                    </div>
                </div>

                <div v-if="has_parked_today" class="section-wrapper">
                    <h5 class="section-title">
                        <span class="mdi mdi-parking"></span> Previously, you have parked at ...<br>
                    </h5>
                    <table class="mt-4">
                        <tbody>
                            <tr>
                                <td>Parking Zone</td>
                                <td>&nbsp;:&nbsp;</td>
                                <td>{{ latest_record.parking_zone }}</td>
                            </tr>
                            <tr>
                                <td>Enter time</td>
                                <td>&nbsp;:&nbsp;</td>
                                <td>{{ latest_record.time_in }}</td>
                            </tr>
                            <tr>
                                <td>Exit time</td>
                                <td>&nbsp;:&nbsp;</td>
                                <td>{{ latest_record.time_out }}</td>
                            </tr>
                            <tr>
                                <td>Duration</td>
                                <td>&nbsp;:&nbsp;</td>
                                <td>
                                    {{ `${latest_record.hours}h ${latest_record.minutes}m` }}
                                </td>
                            </tr>
                            <tr>
                                <td>Parking fee</td>
                                <td>&nbsp;:&nbsp;</td>
                                <td>RM{{ (latest_record.fee / 100).toFixed(2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h1>Parking history</h1>
                <div class="section-wrapper" v-if="parking_records.length == 0">
                    No records.
                </div>
                <div class="section-wrapper" v-else>
                    <div v-for="(record, index) in parking_records" :key=index>
                        <h5 class="section-title">{{ index }}</h5>
                        <div class="section-child-wrapper" v-for="(data, i) in record" :key=i>
                            <div class="w-100">
                                <div class="parking-date text-center pt-2">
                                    <span class="secondary-txt">
                                        <span class="mdi mdi-boom-gate"></span> Parking zone:&nbsp;
                                    </span>
                                    {{ data.parking_zone }}
                                </div>
                            </div>
                            <div class="d-flex flex-wrap justify-content-start mt-2">
                                <div class="parking-date text-center pt-2">
                                    <span class="secondary-txt">
                                        <span class="mdi mdi-location-enter"></span> Time in:<br>
                                    </span>
                                    {{ data.time_in }}
                                </div>
                                <div class="parking-date text-center pt-2">
                                    <span class="secondary-txt">
                                        <span class="mdi mdi-location-exit"></span> Time out:<br>
                                    </span>
                                    {{ data.time_out ? data.time_out : 'In car park' }}
                                </div>
                            </div>
                            <div class="d-flex flex-wrap justify-content-start mt-2">
                                <div class="parking-date text-center pt-2">
                                    <span class="secondary-txt">
                                        <span class="mdi mdi-timer-outline"></span> Duration:<br>
                                    </span>
                                    {{ `${data.duration.hours}h ${data.duration.minutes}m` }}
                                </div>
                                <div class="parking-date text-center pt-2">
                                    <span class="secondary-txt">
                                        <span class="mdi mdi-credit-card"></span> Parking fee:<br>
                                    </span>
                                    {{ `RM ${(data.fee / 100).toFixed(2)}` }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import ParkingAvailabilityChart from "../components/charts/ParkingAvailability.vue";

    export default {
        components: {
            ParkingAvailabilityChart
        },
        data() {
            return {
                is_in_parking: false,
                car_state: {},
                estimated_fee: 0,
                has_parked_today: false,
                latest_record: {
                    hours: 0,
                    minutes: 0,
                },
                parking_records: {},
            }
        },
        mounted() {
            this.getCarState();
            this.getParkingRecords();
        },
        methods: {
            getCarState() {
                axios
                    .get('/parking/state')
                    .then((result) => {
                        if (result.data.isInParking) {
                            this.is_in_parking = true;
                            this.car_state = result.data.data[0];
                            this.estimateParkingFee();
                        }
                        if (result.data.hasParkedToday) {
                            this.has_parked_today = true;
                            if (result.data.isInParking) {
                                this.latest_record = result.data.data[1];
                            } else {
                                this.latest_record = result.data.data[0];
                            }
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
                        this.estimated_fee = (result.data / 100).toFixed(2);
                        this.$forceUpdate();
                    });
            },
            toDateString(_date) {
                return _date.getFullYear() + '-' +
                        ("0" + (_date.getMonth() + 1)).slice(-2) + '-' +
                        ("0" + _date.getDate()).slice(-2);
            },
            toTimeString(_date) {
                return ("0" + _date.getHours()).slice(-2) + ':' +
                        ("0" + _date.getMinutes()).slice(-2) + ':' +
                        ("0" + _date.getSeconds()).slice(-2);
            },
            getWeekDay(_date){
                let weekdays = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
                return weekdays[_date.getDay()];
            },
            getParkingRecords() {
                axios
                    .get('/parking/records')
                    .then((result) => {
                        this.parking_records = _.groupBy(result.data.data, record => {
                            let _date = new Date(record.time_in.replace(/-/g, '/'));
                            return `${this.toDateString(_date)}, ${this.getWeekDay(_date)}`;
                        });
                        for (var group in this.parking_records) {
                            _.map(this.parking_records[group], record => {
                                let _hours = Math.floor(record.duration);
                                let _minutes = Math.floor((record.duration - _hours) * 60);
                                record.duration = {
                                    hours: _hours,
                                    minutes: _minutes
                                };
                                record.time_in = this.toTimeString(new Date(record.time_in.replace(/-/g, '/')));
                                record.time_out = (record.time_out)
                                                ? this.toTimeString(new Date(record.time_out.replace(/-/g, '/')))
                                                : null;
                                return record;
                            });
                        };
                    });
            },
        }
    }
</script>

<style lang="scss">
    @import './resources/sass/_variables.scss';

    .parking-zone {
        color: $blue;
        font-size: 1.5rem;
    }

    .secondary-txt {
        color: $secondary-txt;
    }
    .tertiary-txt {
        color: $tertiary-txt;
    }

    .parking-date {
        flex: 1 1 180px;
        font-size: 1.2em;
    }

    .record-label {
        flex-basis: 130px;
        width: 100%;
    }
    .record-text {
        flex-basis: 130px;
        width: 100%;
    }
</style>
