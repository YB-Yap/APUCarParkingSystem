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
                            <span>{{ "RM" + (user.apcard_balance / 100).toFixed(2) }}</span>
                        </div>
                    </div>
                    <div v-masonry-tile class="col-md-6 col-lg-4 dashboard-block">
                        <div class="block-content">
                            <h5 class="block-title">
                                <span class="mdi mdi-parking"></span>
                                Car Park Status
                            </h5>
                            <span>{{ parking_availability }}</span>
                        </div>
                    </div>
                    <div v-masonry-tile class="col-md-6 col-lg-4 dashboard-block">
                        <div class="block-content" style="height: 200px;">
                            <h5 class="block-title">
                                <span class="mdi mdi-eye"></span>
                                Parking Status
                            </h5>
                            <div v-if="is_in_parking">
                                <span>Your car is currently parked in Zone {{ car_state.parking_zone }}.</span><br>
                                <span>Enter time: {{ car_state.time_in }}</span><br>
                                <span>Estimated parking fee: RM{{ estimated_fee }}</span>
                            </div>
                            <div v-else>
                                <span>Your car is not parked in any Zone.</span>
                            </div>
                        </div>
                    </div>
                    <div v-if="!is_in_parking && has_parked_today" v-masonry-tile class="col-md-6 col-lg-4 dashboard-block">
                        <div class="block-content" style="height: 200px;">
                            <h5 class="block-title">
                                <span class="mdi mdi-eye"></span>
                                Today, you have parked at ...
                            </h5>
                            <span>Parking Zone: {{ latest_record.parking_zone }}.</span><br>
                            <span>Enter time: {{ latest_record.time_in }}</span><br>
                            <span>Exit time: {{ latest_record.time_out }}</span><br>
                            <span>Duration: {{ latest_record.duration }}</span><br>
                            <span>Parking fee: RM{{ latest_record.fee }}</span>
                        </div>
                    </div>
                    <div v-masonry-tile class="col-md-6 col-lg-4 dashboard-block" v-for="item in 5" :key="item">
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
                    <span class="nav-icon mdi mdi-eye"></span>
                    <span>Parking Status</span>
                </router-link>
                <router-link to="car-park-status" class="nav-link">
                    <span class="nav-icon mdi mdi-parking"></span>
                    <span>Car Park Status</span>
                </router-link>
                <router-link :to="{name: 'simulator', params: {user_id: user.id}}" class="nav-link">
                    <span class="nav-icon mdi mdi-alert-circle"></span>
                    <span>Simulator</span>
                </router-link>
                <router-link to="more" class="nav-link">
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
                parking_availability: 0,
                is_in_parking: false,
                car_state: {},
                estimated_fee: 0,
                has_parked_today: false,
            }
        },
        mounted() {
            this.getCarParkAvailability();
            this.getCarState();
        },
        methods: {
            getCarParkAvailability() {
                axios
                    .get('/api/availability/carpark')
                    .then((result) => {
                        this.parking_availability = result.data;
                    });
            },
            getLastestRecord() {
                this.has_parked_today = true;
                this.$forceUpdate();
            },
            getCarState() {
                axios
                    .get('/get-car-state')
                    .then((result) => {
                        console.log(result.data)
                        if (result.data.isInParking) {
                            this.is_in_parking = true;
                            this.car_state = result.data.data;
                        } else if (!result.data.isInParking && result.data.data != null) {
                            this.getLastestRecord();
                        }
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
