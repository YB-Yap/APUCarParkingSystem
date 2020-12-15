<template>
    <div class="page">
        <div class="page-header">
            <h1 class="page-title">View Active Subscriptions</h1>
        </div>
        <div class="page-content">
            <div class="center-container">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><span @click="goTo()">Subscription</span></li>
                        <li class="breadcrumb-item active" aria-current="page">Active Subscriptions</li>
                    </ol>
                </nav>

                <h1>Season Parking Subscription</h1>
                <div class="section-wrapper">
                    <h5 class="section-title text-center">
                        <span class="mdi mdi-calendar-clock"></span>
                        Subscription Availability
                    </h5>
                    <div class="my-2">
                        <SubscriptionAvailabilityChart style="height: 130px;" />
                    </div>
                </div>
                <h1>Active Subscriptions</h1>
                <div class="section-wrapper" v-if="Object.keys(subscription_state).length > 0">
                    <hr>
                    <div v-for="(subs, index) in subscription_state" :key="index">
                        <h5>{{ `${subs[0].user.fullname}, ${subs[0].user.tp_number}` }}</h5>
                        <div class="section-child-wrapper">
                            <div class="d-flex flex-wrap justify-content-start">
                                <div class="subs-date text-center my-1">
                                    <span class="secondary-txt">
                                        <span class="mdi mdi-timer-outline"></span> Valid date:<br>
                                    </span>
                                    {{ subs[0].valid_at }} ~ {{ subs[subs.length -1].valid_till }}
                                </div>
                                <div class="subs-date text-center my-1">
                                    <span class="secondary-txt">
                                        <span class="mdi mdi-timer"></span> Duration:<br>
                                    </span>
                                    {{ subs.length }} month(s)
                                </div>
                            </div>
                        </div>
                        <hr>
                    </div>
                </div>
                <div class="section-wrapper" v-else>
                    There are no active subscriptions.
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import SubscriptionAvailabilityChart from "../../components/charts/SubscriptionAvailability.vue";

    export default {
        components: {
            SubscriptionAvailabilityChart,
        },
        data() {
            return {
                subscription_availability: 0,
                subscription_size: 0,
                subscription_state: [],
                estimated_date: '',
            }
        },
        mounted() {
            this.getSubscriptionAvailability();
            this.getSubscriptionSize();
            this.getActiveSubs();
        },
        methods: {
            goTo() {
                this.$router.push('/admin/subscription');
            },
            toDateString(_date) {
                return _date.getFullYear() + '-' +
                        ("0" + (_date.getMonth() + 1)).slice(-2) + '-' +
                        ("0" + _date.getDate()).slice(-2);
            },
            getActiveSubs() {
                this.$swal({
                    title: 'Loading subscriptions',
                    text: 'Please wait.',
                    icon: 'info',
                    allowOutsideClick: false,
                })
                this.$swal.showLoading();
                axios
                    .get('/admin/subscription/all-active')
                    .then((result) => {
                        this.$swal.close();
                        this.subscription_state = _.groupBy(result.data, record => {
                            return record.user_id;
                        });
                    });
            },
            estimateSubsRestockDate() {
                axios
                    .get('/api/subscription/estimate-restock-date')
                    .then((result) => {
                        this.estimated_date = result.data.estimatedDate;
                    });
            },
            getSubscriptionAvailability() {
                axios
                    .get('/api/subscription/availability')
                    .then((result) => {
                        this.subscription_availability = result.data;
                        if (this.subscription_availability == 0) {
                            this.estimateSubsRestockDate();
                        }
                    });
            },
            getSubscriptionSize() {
                axios
                    .get('/api/subscription/size')
                    .then((result) => {
                        this.subscription_size = result.data;
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
