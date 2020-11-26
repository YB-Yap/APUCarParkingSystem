<template>
    <div class="content-container">
        <div class="content">
            <router-view></router-view>
        </div>
        <div class="btm-navbar">
            <div class="row flex flex-nowrap justify-content-center">
                <router-link to="/admin/dashboard" class="nav-link">
                    <span class="nav-icon mdi mdi-home"></span>
                    <span>Dashboard</span>
                </router-link>
                <!-- <router-link to="parking-status" class="nav-link">
                    <span class="nav-icon mdi mdi-parking"></span>
                    <span>Parking Status</span>
                </router-link> -->
                <router-link to="/admin/subscription" class="nav-link">
                    <span class="nav-icon mdi mdi-calendar-clock"></span>
                    <span>Subscription</span>
                </router-link>
                <router-link to="/admin/settings" class="nav-link">
                    <span class="nav-icon mdi mdi-cog"></span>
                    <span>Settings</span>
                </router-link>
                <router-link to="/admin/more" class="nav-link">
                    <span class="nav-icon mdi mdi-dots-vertical"></span>
                    <span>More</span>
                </router-link>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            if (window.location.pathname == '/') {
                this.$router.push('dashboard');
            }
            if (window.location.pathname == '/admin') {
                this.$router.push('/admin/dashboard');
            }
        },
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
</style>
