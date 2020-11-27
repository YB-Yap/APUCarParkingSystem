<template>
    <div class="page">
        <div class="page-header">
            <h1 class="page-title">View Parking Log</h1>
        </div>
        <div class="page-content">
            <div class="container">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><span @click="goTo()">View Logs</span></li>
                        <li class="breadcrumb-item active" aria-current="page">Parking Log</li>
                    </ol>
                </nav>
                parking log
                <pagination :data="parking_logs" align="right" @pagination-change-page="getParkingLog"></pagination>

                <ul>
                    <li v-for="log in parking_logs.data" :key="log.id">{{ log }}</li>
                </ul>

                <pagination :data="parking_logs" align="right" @pagination-change-page="getParkingLog"></pagination>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                parking_logs: {},
            }
        },
        mounted() {
            this.getParkingLog();
        },
        methods: {
            goTo() {
                this.$router.push('/admin/logs');
            },
            getParkingLog(page = 1) {
                axios
                    .get(`/api/admin/parking?page=${page}`)
                    .then((result) => {
                        console.log(result.data);
                        this.parking_logs = result.data;
                    });
            },
        }
    }
</script>

<style lang="scss">
    @import './resources/sass/_variables.scss';

    .breadcrumb {
        background-color: $tertiary-bg;

        .breadcrumb-item {
            color: $blue;

            &:hover {
                text-decoration: underline;
                cursor: pointer;
            }
        }

        .active {
            color: $main-txt;

            &:hover {
                text-decoration: none;
                cursor: default;
            }
        }
    }

</style>
