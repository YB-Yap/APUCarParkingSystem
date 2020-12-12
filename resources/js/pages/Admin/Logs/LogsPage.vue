<template>
    <div class="page">
        <div class="page-header">
            <h1 class="page-title">View Logs</h1>
        </div>
        <div class="page-content">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 col-lg-4 log-block">
                        <div class="block-content" @click="redirect('parking')">
                            Parking logs
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 log-block">
                        <div class="block-content" @click="redirect('subscription')">
                            Subscription logs
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    methods: {
        redirect(page) {
            this.$router.push(`/admin/logs/${page}`);
        },
    }
}
</script>

<style lang="scss">
    @import './resources/sass/_variables.scss';

    .log-block {
        padding: 20px;
        width: 100%;

        .block-content {
            background-color: $tertiary-bg;
            border: 1px solid $tertiary-bg;
            color: $main-txt;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
            padding: 15px;
            cursor: pointer;

            &:hover {
                border-color: $blue;
            }
        }
    }
</style>
