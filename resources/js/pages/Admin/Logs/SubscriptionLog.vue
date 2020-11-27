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
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        methods: {
            goTo() {
                this.$router.push('/admin/logs');
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
