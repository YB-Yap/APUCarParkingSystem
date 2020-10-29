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
            <div class="row flex flex-nowrap justify-content-center">
                <router-link to="/dashboard" class="nav-link">
                    <!-- <a class="nav-link"> -->
                        <span class="nav-icon mdi mdi-home"></span>
                        <span>Dashboard</span>
                    <!-- </a> -->
                </router-link>
                <router-link to="parking-status" class="nav-link">
                    <!-- <a class="nav-link"> -->
                        <span class="nav-icon mdi mdi-eye"></span>
                        <span>Parking Status</span>
                    <!-- </a> -->
                </router-link>
                <router-link to="car-park-status" class="nav-link">
                    <!-- <a class="nav-link"> -->
                        <span class="nav-icon mdi mdi-parking"></span>
                        <span>Car Park Status</span>
                    <!-- </a> -->
                </router-link>
                <!-- <a class="nav-link" href="/logout"> -->
                <a class="nav-link" @click="logout()">
                    <span class="nav-icon mdi mdi-logout"></span>
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
            // display: block;
            // align-self: center;
            // max-width: 100%;
            text-overflow: ellipsis;
            white-space: nowrap;
            overflow: hidden;
            text-decoration: none;
            flex-basis: 168px;
            text-align: center;

            &:hover {
                color: $blue;
            }
        }

        .btm-nav-active {
            color: $blue;
        }
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
                    background-color: $secondary-bg;
                    color: $main-txt;
                    width: 100%;
                    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
                    border-radius: 6px;
                }
            }


        }
    }
</style>
