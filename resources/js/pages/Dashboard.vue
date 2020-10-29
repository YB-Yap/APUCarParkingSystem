<template>
    <div class="content-container">
        <div class="content">
            <!-- content -->
            <div v-if="isDashboard()" class="dashboard-wrapper">
                <!-- col-md-6 col-lg-4  -->
                <div v-for="col in 3" :key="col" class="dashboard-column">
                    <div v-for="row in 4" :key="row" class="dashboard-block">
                        <div class="block-content">
                            <h1>Welcome back, {{ user.fullname + col + row }}</h1>
                        </div>
                    </div>
                </div>
            </div>
            <router-view v-else></router-view>
        </div>
        <div class="btm-navbar">
            <div class="row justify-content-center">
                <router-link to="/dashboard">
                    <a class="nav-link">
                        <span class="mdi mdi-home"></span>
                        <span>Dashboard</span>
                    </a>
                </router-link>
                <router-link to="parking-status">
                    <a class="nav-link">
                        <span class="mdi mdi-account"></span>
                        <span>Parking Status</span>
                    </a>
                </router-link>
                <router-link to="car-park-status">
                    <a class="nav-link">
                        <span class="mdi mdi-car"></span>
                        <span>Car Park Status</span>
                    </a>
                </router-link>
                <!-- <a class="nav-link" href="/logout"> -->
                <a class="nav-link" @click="logout()">
                    <span class="mdi mdi-account"></span>
                    <span>Logout</span>
                </a>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            user: Object
        },
        // data: function () {
        //     return {
        //         userFullname: "",
        //     }
        // },
        methods: {
            isDashboard: function() {
                return this.$route.path === '/dashboard'
            },
            logout: function() {
                console.log($('.btm-navbar'))
                console.log(`logout`)

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
        height: calc(100% - 57px);
        background-color: $secondary-bg;
        overflow-x: hidden;
        overflow-y: auto;
    }

    .btm-navbar {
        position: absolute;
        z-index: 100;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 57px;
        background-color: $main-bg;
        color: $main-txt;
    }

    .dashboard-wrapper {
        display: flex;
        flex-wrap: wrap;
        flex-direction: row;
        // width: 100%;

        .dashboard-column {
            // width: 100%;
            // height: auto;
            flex-basis: 100%;
            // display: flex;
            // flex-direction: column;
            flex-grow: 1;

            @media (min-width: 768px) {
                // width: 50%;
                flex-basis: 50%;
            }

            @media (min-width: 992px) {
                // width: 33.333%;
                flex-basis: 33.333%;
            }

            .dashboard-block {
                padding: 20px;
                width: 100%;

                .block-content {
                    display: flex;
                    padding: 15px;
                    background-color: $tertiary-bg;
                    color: $main-txt;
                    width: 100%;
                }
            }


        }
    }
</style>
