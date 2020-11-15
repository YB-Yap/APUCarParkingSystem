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
                    <div v-masonry-tile class="col-md-6 col-lg-4 dashboard-block">
                        <div class="block-content text-center">
                            <span class="block-icon mdi mdi-credit-card"></span><br>
                            <span>APCard Balance</span><br>
                            <span>{{ "RM" + this.apcard_balance }}</span>
                        </div>
                    </div>
                    <div v-masonry-tile class="col-md-6 col-lg-4 dashboard-block">
                        <div class="block-content" style="height: 200px;">
                            <h5 class="block-title">
                                <span class="mdi mdi-parking"></span>
                                Parking Status
                            </h5>
                            <div v-if="parking.is_in_parking">
                                <span>Your car is currently parked in Zone {{ parking.car_state.parking_zone }}.</span><br>
                                <span>Enter time: {{ parking.car_state.time_in }}</span><br>
                                <span>Estimated parking fee: RM{{ parking.estimated_fee }}</span>
                            </div>
                            <div v-else>
                                <span>Your car is not parked in any Zone.</span><br>
                                <span>Zone A: {{ parking.availability.zone_a }}</span><br>
                                <span>Zone B: {{ parking.availability.zone_b }}</span>
                            </div>
                        </div>
                    </div>
                    <div v-if="parking.has_parked_today" v-masonry-tile class="col-md-6 col-lg-4 dashboard-block">
                        <div class="block-content" style="height: 200px;">
                            <h5 class="block-title">
                                <span class="mdi mdi-parking"></span>
                                Previously, you have parked at ...
                            </h5>
                            <span>Parking Zone: {{ parking.latest_record.parking_zone }}</span><br>
                            <span>Enter time: {{ parking.latest_record.time_in }}</span><br>
                            <span>Exit time: {{ parking.latest_record.time_out }}</span><br>
                            <span>Duration: {{ `${parking.latest_record.hours} hour(s) ${parking.latest_record.minutes} minute(s)` }}</span><br>
                            <span>Parking fee: RM{{ (parking.latest_record.fee / 100).toFixed(2) }}</span>
                        </div>
                    </div>
                    <div v-masonry-tile class="col-md-6 col-lg-4 dashboard-block">
                        <div class="block-content" style="height: 200px;">
                            <h5 class="block-title">
                                <span class="mdi mdi-calendar-clock"></span>
                                Subscription Status
                            </h5>
                            <div v-if="subscription.has_subs">
                                Your subscription is currently active.<br>
                                Valid from: {{ subscription.valid_from }}<br>
                                Valid till: {{ subscription.valid_till }}
                            </div>
                            <div v-else>
                                You don't have any subscription.<br>
                                Availability: {{ subscription.availability }} of {{ subscription.size }}
                                <div v-if="subscription.availability == 0">
                                    <span>Sorry, there are no subscription available at the moment.</span><br>
                                    <span>Estimated restock date: {{ subscription.estimated_date }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-masonry-tile class="col-md-6 col-lg-4 dashboard-block" v-for="item in 4" :key="item">
                        <div class="block-content">
                            <img style="width: 100%" src="http://via.placeholder.com/350x150">
                            <br>
                            <h5><strong>{{ item }}</strong></h5>
                            <p>{{ item + " testing masonry" }}</p>
                            <p v-if="item == 1">{{ item + " testing masonry" }}</p>
                            <p v-if="item == 1">{{ item + " testing masonry" }}</p>
                            <p v-if="item == 1">{{ item + " testing masonry" }}</p>
                            <p v-if="item == 1">{{ item + " testing masonry" }}</p>
                            <p v-if="item == 1">{{ item + " testing masonry" }}</p>
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
                <router-link to="parking-status" class="nav-link">
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
                </router-link>
                <router-link :to="{name: 'more', params: {user_id: user.id}}" class="nav-link">
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
            this.getAPCardBalance();
            this.getCarParkAvailability();
            this.getCarState();
            this.getSubscriptionState();
            this.getSubscriptionAvailability();
            this.getSubscriptionSize();
        },
        methods: {
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
                return this.$route.path === '/dashboard'
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
