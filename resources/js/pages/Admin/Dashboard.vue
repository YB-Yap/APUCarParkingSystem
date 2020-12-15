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
                                <span class="mdi mdi-list-status"></span> View active subscriptions
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
                parking: {
                    availability: {},
                    car_state: {},
                },
                subscription: {
                    availability: 0,
                    size: 0,
                }
            }
        },
        mounted() {
            this.getUserProfile();
            this.getCarParkAvailability();
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
            getCarParkAvailability() {
                axios
                    .get('/api/parking/availability')
                    .then((result) => {
                        this.parking.availability = result.data;
                    });
            },
            toDateString(_date) {
                return _date.getFullYear() + '-' + ("0" + (_date.getMonth() + 1)).slice(-2) + '-' + ("0" + _date.getDate()).slice(-2);
            },
            getSubscriptionAvailability() {
                axios
                    .get('/api/subscription/availability')
                    .then((result) => {
                        this.subscription.availability = result.data;
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
