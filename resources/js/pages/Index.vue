<template>
    <div class="content-container">
        <div class="content">
            <!-- content -->
            <router-view></router-view>
            <!-- <router-view ref="mainView" :key="$route.fullPath" class="m-8" :action="action"></router-view> -->
        </div>
        <div class="btm-navbar">
            <div class="row justify-content-center">
                <router-link to="dashboard">
                    <a class="nav-link">
                        <span class="mdi mdi-account"></span>
                        <span>Dashboard</span>
                    </a>
                </router-link>
                <router-link to="car-park-status">
                    <a class="nav-link">
                        <span class="mdi mdi-car"></span>
                        <span>Car Park Status</span>
                    </a>
                </router-link>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
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
        bottom: 0;
        left: 0;
        width: 100%;
        height: 57px;
        background-color: $main-bg;
        color: $main-txt;
    }
</style>
