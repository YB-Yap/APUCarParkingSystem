<template>
    <div class="page">
        <div class="page-header">
            <h1 class="page-title">View Subscription Logs</h1>
        </div>
        <div class="page-content">
            <div class="container">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <span @click="goTo()">
                                <span class="mdi mdi-arrow-left"></span> View Logs
                            </span>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Subscription Logs</li>
                    </ol>
                </nav>
                <pagination :data="subscription_logs" :show-disabled="true" align="right" @pagination-change-page="getSubscriptionLogs">
                    <span slot="prev-nav"><span class="mdi mdi-chevron-left"></span></span>
	                <span slot="next-nav"><span class="mdi mdi-chevron-right"></span></span>
                </pagination>
                <div class="table-responsive">
                    <table class="table table-dark table-striped table-hover">
                        <caption>
                            {{ `Currently showing page ${subscription_logs.current_page}
                            of ${subscription_logs.last_page} page(s)` }}
                        </caption>
                        <thead class="thead-dark">
                            <tr>
                            <th scope="col">#</th>
                            <th scope="col">TP Number</th>
                            <th scope="col">Name</th>
                            <th scope="col">Payment Method</th>
                            <th scope="col">Description</th>
                            <th scope="col">Created at</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="log in subscription_logs.data" :key="log.id">
                                <th scope="row">{{ log.id }}</th>
                                <td>{{ log.user.tp_number }}</td>
                                <td>{{ log.user.fullname }}</td>
                                <td>{{ (log.payment_method == 'apcard') ? ' Online via APCard' : 'Via admin counter' }}</td>
                                <td :class="`text-${log.style}`">{{ log.description }}</td>
                                <td>{{ log.created_at }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <pagination :data="subscription_logs" :show-disabled="true" align="right" @pagination-change-page="getSubscriptionLogs">
                    <span slot="prev-nav"><span class="mdi mdi-chevron-left"></span></span>
	                <span slot="next-nav"><span class="mdi mdi-chevron-right"></span></span>
                </pagination>
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
            this.getSubscriptionLogs();
        },
        methods: {
            goTo() {
                this.$router.push('/admin/logs');
            },
            getSubscriptionLogs(page = 1) {
                axios
                    .get(`/api/admin/subscription?page=${page}`)
                    .then((result) => {
                        this.subscription_logs = result.data;
                        _.map(this.subscription_logs.data, record => {
                            if (record.description.toLowerCase().includes('purchase')) {
                                record.style = 'success';
                            } else if (record.description.toLowerCase().includes('extend')) {
                                record.style = 'info';
                            } else if (record.description.toLowerCase().includes('terminate')) {
                                record.style = 'danger';
                            }
                            return record;
                        });
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

    .pagination {
        background-color: $secondary-bg;

        .page-link {
            color: $main-txt;
            border-radius: 0.25rem;
            border-color: $tertiary-bg;
            background-color: $tertiary-bg;
            margin-right: 6px;
            margin-top: 6px;
            margin-bottom: 6px;

            &:hover {
                background-color: $secondary-bg;
                border-color: $blue;
            }
        }

        .disabled .page-link {
            border-color: rgba($tertiary-bg, 0.3);
            background-color: rgba($tertiary-bg, 0.3);
        }
    }

    .table {
        .thead-dark {
            th {
                background-color: $secondary-bg;
                height: 5rem;
            }
        }

        .small-column {
            width: 110px;
        }
    }
</style>
