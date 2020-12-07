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
                    <div class="block-content">
                        <h5 class="block-title">
                            <span class="mdi mdi-parking"></span> Parking Availability
                        </h5>
                        <div class="my-2">
                            <ParkingAvailabilityChart style="height: 150px;" />
                        </div>
                    </div>
                </div>
                <div v-masonry-tile class="col-md-6 col-lg-4 dashboard-block">
                    <div class="block-content">
                        <h5 class="block-title">
                            <span class="mdi mdi-calendar-clock"></span>
                            Subscription Availability
                        </h5>
                        <div class="my-2">
                            <SubscriptionAvailabilityChart style="height: 130px;" />
                        </div>
                        <router-link to="/admin/subscription/active">
                            <button class="btn btn-primary d-block mt-4 w-100">
                                <span class="mdi mdi-list-status"></span> View active subscription
                            </button>
                        </router-link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import ParkingAvailabilityChart from "../../components/charts/ParkingAvailability.vue";
    import SubscriptionAvailabilityChart from "../../components/charts/SubscriptionAvailability.vue";

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
                // apcard_balance: 0,
                parking: {
                    availability: {},
                    // is_in_parking: false,
                    car_state: {},
                    // estimated_fee: 0,
                    // has_parked_today: false,
                    // latest_record: {
                    //     hours: 0,
                    //     minutes: 0,
                    // },
                },
                subscription: {
                    availability: 0,
                    size: 0,
                //     has_subs: false,
                //     state: [],
                //     valid_from: '',
                //     valid_till: '',
                    estimated_date: '',
                }
            }
        },
        mounted() {
            this.getUserProfile();
            // this.getAPCardBalance();
            this.getCarParkAvailability();
            // this.getCarState();
            // this.getSubscriptionState();
            this.getSubscriptionAvailability();
            this.getSubscriptionSize();
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
            // getAPCardBalance() {
            //     axios
            //         .get('/apcard/balance')
            //         .then((result) => {
            //             this.apcard_balance = (result.data / 100).toFixed(2);
            //         });
            // },
            getCarParkAvailability() {
                axios
                    .get('/api/parking/availability')
                    .then((result) => {
                        this.parking.availability = result.data;
                    });
            },
            // getCarState() {
            //     axios
            //         .get('/parking/state')
            //         .then((result) => {
            //             console.log(result.data)
            //             if (result.data.isInParking) {
            //                 this.parking.is_in_parking = true;
            //                 this.parking.car_state = result.data.data[0];
            //                 this.estimateParkingFee();
            //             }
            //             if (result.data.hasParkedToday) {
            //                 this.parking.has_parked_today = true;
            //                 this.parking.latest_record = result.data.data[1];
            //                 this.parking.latest_record.hours = Math.floor(this.parking.latest_record.duration);
            //                 let minutes = (this.parking.latest_record.duration - this.parking.latest_record.hours) * 60;
            //                 this.parking.latest_record.minutes = Math.floor(minutes);
            //             }
            //             this.$forceUpdate();
            //         });
            // },
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
            // getSubscriptionState() {
            //     axios
            //         .get('/subscription/state')
            //         .then((result) => {
            //             console.log(result.data)
            //             if (result.data.hasSubscription) {
            //                 this.subscription.has_subs = true;
            //                 this.subscription.state = result.data.data;
            //             } else {
            //                 this.subscription.has_subs = false;
            //                 this.subscription.state = [];
            //             }

            //             if (this.subscription.has_subs) {
            //                 let last_index = this.subscription.state.length - 1;
            //                 let _from = new Date(this.subscription.state[0].valid_at.replace(/-/g, '/'));
            //                 let _till = new Date(this.subscription.state[last_index].valid_till.replace(/-/g, '/'));
            //                 this.subscription.valid_from = this.toDateString(_from);
            //                 this.subscription.valid_till = this.toDateString(_till);
            //             }
            //         });
            // },
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
            getSubscriptionSize() {
                axios
                    .get('/api/subscription/size')
                    .then((result) => {
                        this.subscription.size = result.data;
                    });
            },
            isDashboard() {
                return this.$route.path === '/admin/dashboard'
            }
        }
    }
</script>

<style lang="scss">
    // import variables.scss
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
</style>
