<template>
    <div class="content-container">
        <div class="content">
            <!-- content -->
            <div v-if="isDashboard()" class="dashboard-wrapper">
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
                </div>
            </div>
            <router-view v-else></router-view>
        </div>
        <div class="btm-navbar">
            <div class="row flex flex-nowrap justify-content-center">
                <router-link to="dashboard" class="nav-link">
                    <span class="nav-icon mdi mdi-home"></span>
                    <span>Dashboard</span>
                </router-link>
                <!-- <router-link to="parking-status" class="nav-link">
                    <span class="nav-icon mdi mdi-parking"></span>
                    <span>Parking Status</span>
                </router-link>
                <router-link :to="{name: 'subscription', params: {user_id: user.id}}" class="nav-link">
                    <span class="nav-icon mdi mdi-calendar-clock"></span>
                    <span>Subscription</span>
                </router-link>
                <router-link :to="{name: 'simulator', params: {user_id: user.id}}" class="nav-link">
                    <span class="nav-icon mdi mdi-alert-circle"></span>
                    <span>Simulator</span>
                </router-link> -->
                <router-link :to="{name: 'admin_more', params: {user_id: user.id}}" class="nav-link">
                    <span class="nav-icon mdi mdi-dots-vertical"></span>
                    <span>More</span>
                </router-link>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            user: Object
        },
        data() {
            return {
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
            // this.getAPCardBalance();
            // this.getCarParkAvailability();
            // this.getCarState();
            // this.getSubscriptionState();
            // this.getSubscriptionAvailability();
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
            getAPCardBalance() {
                axios
                    .get('/apcard/balance')
                    .then((result) => {
                        this.apcard_balance = (result.data / 100).toFixed(2);
                    });
            },
            getCarParkAvailability() {
                axios
                    .get('/api/parking/availability')
                    .then((result) => {
                        this.parking.availability = result.data;
                    });
            },
            getCarState() {
                axios
                    .get('/parking/get-state')
                    .then((result) => {
                        console.log(result.data)
                        if (result.data.isInParking) {
                            this.parking.is_in_parking = true;
                            this.parking.car_state = result.data.data[0];
                            this.estimateParkingFee();
                        }
                        if (result.data.hasParkedToday) {
                            this.parking.has_parked_today = true;
                            this.parking.latest_record = result.data.data[1];
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
                    .get('/subscription/get-state')
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
                            let _from = new Date(this.subscription.state[0].valid_at);
                            let _till = new Date(this.subscription.state[last_index].valid_till);
                            this.subscription.valid_from = this.toDateString(_from);
                            this.subscription.valid_till = this.toDateString(_till);
                        }
                    });
            },
            estimateSubsRestockDate() {
                axios
                    .get('/api/subscription/estimate-restock-date')
                    .then((result) => {
                        this.subscription.estimated_date = result.data.estimated_date;
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

    .content-container {
        width: 100%;
        height: 100%;
    }
    .content {
        width: 100%;
        height: calc(100% - 58px);
        background-color: $main-bg;
        overflow-x: hidden;
        overflow-y: auto;
    }
    .btm-navbar {
        position: absolute;
        z-index: 100;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 58px;
        background-color: $main-bg;
        color: $main-txt;
        border-top: 1px solid #00000033;

        .nav-icon {
            display: block;
            text-align: center;
            font-size: 23px;
            margin: -10px;
            height: 38px;
        }

        .nav-link {
            color: $secondary-txt;
            text-overflow: ellipsis;
            white-space: nowrap;
            overflow: hidden;
            text-decoration: none;
            flex-basis: 168px;
            text-align: center;
            cursor: pointer;

            &:hover {
                color: $blue;
            }
        }

        .btm-nav-active {
            color: $blue;
        }
    }

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
        }
        .text {
            display: inline-block;
            padding-left: 16px;
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
