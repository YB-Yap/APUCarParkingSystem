<template>
    <div class="page">
        <div class="dashboard-wrapper">
            <div v-masonry transition-duration="0.3s" item-selector=".dashboard-block">
                <div v-masonry-tile class="col-md-6 col-lg-4 dashboard-block">
                    <div class="block-content">
                        <div class="profile">
                            <img :src="user.profile_pic_path" :alt="user.fullname" class="profile-pic">
                            <div class="text">
                                <h5 class="block-title">Welcome back, {{ user.fullname }}</h5>
                                <p class="block-subtitle">Nice to see you again.</p>
                            </div>
                        </div>
                        <div class="profile-links">
                            <a class="link" @click="logout()">
                                <span class="block-icon mdi mdi-logout"></span>
                                <span>Logout</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div v-masonry-tile class="col-md-6 col-lg-4 dashboard-block">
                    <div class="block-content text-center">
                        <span class="block-icon mdi mdi-credit-card"></span><br>
                        <span>APCard Balance</span><br>
                        <span class="card-balance">{{ "RM" + this.apcard_balance }}</span>
                    </div>
                </div>
                <div v-masonry-tile class="col-md-6 col-lg-4 dashboard-block">
                    <div class="block-content">
                        <h5 class="block-title">
                            <span class="mdi mdi-parking"></span>
                            Parking Status
                        </h5>
                        <div v-if="parking.is_in_parking">
                            <span class="d-block w-100 text-center">
                                Your car is currently parked in Zone {{ parking.car_state.parking_zone }}.
                            </span>
                            <div class="d-flex flex-wrap justify-content-start mt-2">
                                <div class="parking-date text-center pt-2">
                                    <span class="secondary-txt">Enter time:</span><br>
                                    {{ parking.car_state.time_in }}
                                </div>
                                <div class="parking-date text-center pt-2">
                                    <span class="secondary-txt">Estimated parking fee:</span><br>
                                    RM{{ parking.estimated_fee }}
                                </div>
                            </div>
                        </div>
                        <div v-else>
                            <span class="d-block w-100 text-center mt-4">
                                Your car is not parked in any zone.
                            </span>
                        </div>
                        <hr>
                        <h5 class="block-title mt-4 text-center">
                            <span class="mdi mdi-parking"></span> Availability
                        </h5>
                        <div class="my-2">
                            <ParkingAvailabilityChart style="height: 150px;" />
                        </div>
                    </div>
                </div>
                <div v-if="parking.has_parked_today" v-masonry-tile class="col-md-6 col-lg-4 dashboard-block">
                    <div class="block-content">
                        <h5 class="block-title">
                            <span class="mdi mdi-parking"></span>
                            Previously, you have parked at ...
                        </h5>
                        <table>
                            <tbody>
                                <tr>
                                    <td>Parking Zone</td>
                                    <td>&nbsp;:&nbsp;</td>
                                    <td>{{ parking.latest_record.parking_zone }}</td>
                                </tr>
                                <tr>
                                    <td>Enter time</td>
                                    <td>&nbsp;:&nbsp;</td>
                                    <td>{{ parking.latest_record.time_in }}</td>
                                </tr>
                                <tr>
                                    <td>Exit time</td>
                                    <td>&nbsp;:&nbsp;</td>
                                    <td>{{ parking.latest_record.time_out }}</td>
                                </tr>
                                <tr>
                                    <td>Duration</td>
                                    <td>&nbsp;:&nbsp;</td>
                                    <td>
                                        {{ `${parking.latest_record.hours}h ${parking.latest_record.minutes}m` }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Parking fee</td>
                                    <td>&nbsp;:&nbsp;</td>
                                    <td>RM{{ (parking.latest_record.fee / 100).toFixed(2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div v-masonry-tile class="col-md-6 col-lg-4 dashboard-block">
                    <div class="block-content">
                        <h5 class="block-title">
                            <span class="mdi mdi-calendar-clock"></span>
                            Subscription Status
                        </h5>
                        <div v-if="subscription.has_subs">
                            <span class="d-block w-100 text-center mt-4">
                                Your subscription is currently active.
                            </span>
                            <div class="d-flex flex-wrap justify-content-start mt-2">
                                <div class="subs-date text-center pt-2">
                                    <span class="secondary-txt">Valid from:</span><br>
                                    {{ subscription.valid_from }}
                                </div>
                                <div class="subs-date text-center pt-2">
                                    <span class="secondary-txt">Valid till:</span><br>
                                    {{ subscription.valid_till }}
                                </div>
                            </div>
                        </div>
                        <div v-else>
                            <span class="d-block w-100 text-center mt-4">
                                You don't have any subscription.
                            </span>

                            <div v-if="subscription.availability == 0">
                                <span class="d-block w-100 text-center">
                                    Sorry, there are no subscription available at the moment.
                                </span>
                                <div class="d-flex flex-wrap justify-content-start mt-2">
                                    <div class="subs-restock text-center secondary-txt">
                                        Estimated restock date:
                                    </div>
                                    <div class="subs-restock text-center">
                                        {{ subscription.estimated_date }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h5 class="block-title mt-4 text-center">
                            <span class="mdi mdi-calendar-clock"></span>
                            Subscription Availability
                        </h5>
                        <div class="my-2">
                            <SubscriptionAvailabilityChart style="height: 130px;" />
                        </div>
                    </div>
                </div><div v-masonry-tile class="col-md-6 col-lg-4 dashboard-block">
                    <div class="block-content">
                        <h5 class="block-title">Today's Schedule</h5>
                        <div class="text-center text-muted mt-4">No class today</div>
                        <div class="mt-3">
                            <span class="text-danger">*Note: </span>
                            The upcoming classes in this section belong to your default intake which is: <b>UC3F2005IT</b><br>
                            * Timetable here is displayed for the intake grouping <b>()</b>.
                            You can change the grouping settings from the timetable page and then refreshing this page.<br>
                            **All times in this section are displayed in Malaysia Time Zone (GMT+8)
                        </div>
                    </div>
                </div>
                <div v-masonry-tile class="col-md-6 col-lg-4 dashboard-block">
                    <div class="block-content">
                        <h5 class="block-title">Financials</h5>
                        <img src="/images/happy.svg" alt="happy" class="happy mt-3">
                        <div class="text-center mt-4 mb-2">
                            <b>No Outstanding Payments need to be done!</b><br>
                            You have cleared all of your outstanding payments.
                        </div>
                    </div>
                </div>
                <div v-masonry-tile class="col-md-6 col-lg-4 dashboard-block">
                    <div class="block-content">
                        <h5 class="block-title">Notice Board</h5>
                        <img src="/images/relocate-car-park.jpg" alt="relocate car park" class="w-100 mt-3">
                        <div class="text-center mt-4">
                            <span class="mdi mdi-circle p-2 text-muted"></span>
                            <span class="mdi mdi-circle p-2 text-primary"></span>
                            <span class="mdi mdi-circle p-2 text-muted"></span>
                            <span class="mdi mdi-circle p-2 text-muted"></span>
                            <span class="mdi mdi-circle p-2 text-muted"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import ParkingAvailabilityChart from "../components/charts/ParkingAvailability.vue";
    import SubscriptionAvailabilityChart from "../components/charts/SubscriptionAvailability.vue";

    export default {
        components: {
            ParkingAvailabilityChart,
            SubscriptionAvailabilityChart,
        },
        data() {
            return {
                user: {
                    profile_pic_path: '',
                    fullname: '',
                },
                apcard_balance: 0,
                parking: {
                    availability: {},
                    is_in_parking: false,
                    car_state: {},
                    estimated_fee: 0,
                    has_parked_today: false,
                    latest_record: {
                        hours: 0,
                        minutes: 0,
                    },
                },
                subscription: {
                    availability: 0,
                    size: 0,
                    has_subs: false,
                    state: [],
                    valid_from: '',
                    valid_till: '',
                    estimated_date: '',
                }
            }
        },
        mounted() {
            this.getUserProfile();
            this.getAPCardBalance();
            // this.getCarParkAvailability();
            this.getCarState();
            this.getSubscriptionState();
            this.getSubscriptionAvailability();
            // this.getSubscriptionSize();
        },
        methods: {
            logout() {
                this.$swal.fire({
                    text: 'Are you sure you want to log out?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Logout',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    // clicked 'Logout'
                    if (result.value) {
                        window.location.href = '/logout'
                    }
                })
            },
            getUserProfile() {
                axios
                    .get('/user/profile')
                    .then((result) => {
                        this.user = result.data;
                    });
            },
            getAPCardBalance() {
                axios
                    .get('/apcard/balance')
                    .then((result) => {
                        this.apcard_balance = (result.data / 100).toFixed(2);
                    });
            },
            // getCarParkAvailability() {
            //     axios
            //         .get('/api/parking/availability')
            //         .then((result) => {
            //             this.parking.availability = result.data;
            //         });
            // },
            getCarState() {
                axios
                    .get('/parking/state')
                    .then((result) => {
                        console.log(result.data)
                        if (result.data.isInParking) {
                            this.parking.is_in_parking = true;
                            this.parking.car_state = result.data.data[0];
                            this.estimateParkingFee();
                        }
                        if (result.data.hasParkedToday) {
                            this.parking.has_parked_today = true;
                            if (result.data.isInParking) {
                                this.parking.latest_record = result.data.data[1];
                            } else {
                                this.parking.latest_record = result.data.data[0];
                            }
                            this.parking.latest_record.hours = Math.floor(this.parking.latest_record.duration);
                            let minutes = (this.parking.latest_record.duration - this.parking.latest_record.hours) * 60;
                            this.parking.latest_record.minutes = Math.floor(minutes);
                        }
                        this.$forceUpdate();
                    });
            },
            estimateParkingFee() {
                axios
                    .get('/parking/estimate-fee')
                    .then((result) => {
                        console.log(result.data)
                        this.parking.estimated_fee = (result.data / 100).toFixed(2);
                        this.$forceUpdate();
                    });
            },
            toDateString(_date) {
                return _date.getFullYear() + '-' + ("0" + (_date.getMonth() + 1)).slice(-2) + '-' + ("0" + _date.getDate()).slice(-2);
            },
            getSubscriptionState() {
                axios
                    .get('/subscription/state')
                    .then((result) => {
                        console.log(result.data)
                        if (result.data.hasSubscription) {
                            this.subscription.has_subs = true;
                            this.subscription.state = result.data.data;
                        } else {
                            this.subscription.has_subs = false;
                            this.subscription.state = [];
                        }

                        if (this.subscription.has_subs) {
                            let last_index = this.subscription.state.length - 1;
                            let _from = new Date(this.subscription.state[0].valid_at.replace(/-/g, '/'));
                            let _till = new Date(this.subscription.state[last_index].valid_till.replace(/-/g, '/'));
                            this.subscription.valid_from = this.toDateString(_from);
                            this.subscription.valid_till = this.toDateString(_till);
                        }
                    });
            },
            estimateSubsRestockDate() {
                axios
                    .get('/api/subscription/estimate-restock-date')
                    .then((result) => {
                        this.subscription.estimated_date = result.data.estimatedDate;
                    });
            },
            getSubscriptionAvailability() {
                axios
                    .get('/api/subscription/availability')
                    .then((result) => {
                        this.subscription.availability = result.data;
                        if (this.subscription.availability == 0) {
                            this.estimateSubsRestockDate();
                        }
                    });
            },
            // getSubscriptionSize() {
            //     axios
            //         .get('/api/subscription/size')
            //         .then((result) => {
            //             this.subscription.size = result.data;
            //         });
            // },
        }
    }
</script>

<style lang="scss">
    @import './resources/sass/_variables.scss';

    .dashboard-block {
        padding: 20px;
        width: 100%;

        .block-content {
            padding: 15px;
            background-color: $secondary-bg;
            color: $main-txt;
            width: 100%;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
            border-radius: 6px;

            .block-title {
                color: $blue;
                font-weight: 700;
            }
            .block-subtitle {
                color: $secondary-txt;
                font-weight: 400;
            }
        }
    }

    .profile {
        display: block;
        width: 100%;

        .profile-pic {
            width: 64px;
            height: 64px;
            object-fit: cover;
            float: left;
            border-radius: 32px;
            margin-right: 16px;
        }
        .text {
            display: inline-block;
        }
    }
    .profile-links {
        display: block;
        width: 100%;
        height: 64px;
        position: relative;

        .link {
            color: $secondary-txt;
            text-overflow: ellipsis;
            white-space: nowrap;
            overflow: hidden;
            text-decoration: none;
            cursor: pointer;
            position: absolute;
            right: 0px;
            bottom: 0px;

            &:hover {
                color: $blue;
            }
        }
    }
    .block-icon {
        font-size: 24px;
    }

    .secondary-txt {
        color: $secondary-txt;
    }

    .card-balance {
        font-size: 1.5em;
    }
    .parking-date {
        flex: 1 1 180px;
        font-size: 1.2em;
    }
    .subs-date {
        flex: 1 1 50%;
        font-size: 1.2em;
    }
    .subs-restock {
        flex: 1 1 180px;
        font-size: 1.2em;
    }

    .happy {
        width: 60%;
        margin-left: auto;
        margin-right: auto;
        display: block;
        height: auto;
    }
</style>
