<template>
    <div class="page">
        <div class="page-header">
            <h1 class="page-title">View Subscription Log</h1>
        </div>
        <div class="page-content">
            <div class="container">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><span @click="goTo()">View Logs</span></li>
                        <li class="breadcrumb-item active" aria-current="page">Subscription Log</li>
                    </ol>
                </nav>
                Subscription log
                <pagination :data="subscription_logs" align="right" @pagination-change-page="getSubscriptionLog"></pagination>

                <ul>
                    <li v-for="log in subscription_logs.data" :key="log.id">{{ log }}</li>
                </ul>

                <pagination :data="subscription_logs" align="right" @pagination-change-page="getSubscriptionLog"></pagination>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                subscription_logs: {},
            }
        },
        mounted() {
            this.getSubscriptionLog();
        },
        methods: {
            goTo() {
                this.$router.push('/admin/logs');
            },
            getSubscriptionLog(page = 1) {
                axios
                    .get(`/api/admin/subscription?page=${page}`)
                    .then((result) => {
                        console.log(result.data);
                        this.subscription_logs = result.data;
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
