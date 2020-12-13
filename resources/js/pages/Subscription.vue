<template>
    <div class="page">
        <div class="page-header">
            <h1 class="page-title">Subscription</h1>
        </div>
        <div class="page-content">
            <div class="center-container">
                <h1>Subscription status</h1>
                <div class="section-wrapper">
                    <span class="d-block w-100 text-center">
                        {{
                            has_subscription
                            ? 'Your subscription is currently active.'
                            : 'You don\'t have any subscription.'
                        }}
                    </span>
                    <router-link to="subscription-history">
                        <button class="btn btn-primary d-block mt-4 w-100">
                            <span class="mdi mdi-list-status"></span> View subscription history
                        </button>
                    </router-link>
                </div>
                <h3 v-if="has_subscription">Owned subscription</h3>
                <div v-if="has_subscription" class="section-wrapper">
                    <div
                        class="section-child-wrapper border"
                        v-for="(sub, index) in subscription_state"
                        :key="index"
                        :class="sub.is_active ? 'border-success' : 'border-info'"
                    >
                        <div class="d-flex flex-wrap justify-content-start">
                            <div class="subs-date text-center my-1">
                                <span class="secondary-txt">
                                    <span class="mdi mdi-timer-outline"></span> Valid date:<br>
                                </span>
                                {{ sub.valid_at }} ~ {{ sub.valid_till }}
                            </div>
                            <div class="subs-date text-center my-1">
                                <span class="secondary-txt">
                                    <span class="mdi mdi-credit-card-outline"></span> Status:<br>
                                </span>
                                <span :class="sub.is_active ? 'text-success' : 'text-primary'">
                                    {{ sub.is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <h1>Season Parking Subscription</h1>
                <div class="section-wrapper" v-if="!has_subscription">
                    <h5 class="section-title text-center">
                        <span class="mdi mdi-calendar-clock"></span>
                        Subscription Availability
                    </h5>
                    <div class="my-2">
                        <SubscriptionAvailabilityChart style="height: 130px;" />
                    </div>
                </div>
                <div class="section-wrapper" v-if="subscription_availability == 0 && !has_subscription">
                    <span class="d-block w-100 text-center">
                        Sorry, there are no subscription available at the moment.
                    </span>
                    <div class="d-flex flex-wrap justify-content-start mt-2">
                        <div class="subs-restock text-center secondary-txt">
                            Estimated restock date:
                        </div>
                        <div class="subs-restock text-center">
                            {{ estimated_date }}
                        </div>
                    </div>
                </div>
                <div class="section-wrapper" v-else>
                    <h5 class="section-title">
                        {{ has_subscription ? 'Extend my subscription' : 'Purchase a subscription' }}
                    </h5>
                    <p>Season Parking Subscription is only for parking zone <strong>B</strong>.</p>
                    <div class="d-flex flex-wrap justify-content-start mt-2">
                        <div class="subs-purchase text-center pt-2">
                            <span class="secondary-txt">
                                <span class="mdi mdi-credit-card-outline"></span> Price:<br>
                            </span>
                            RM 60.00
                        </div>
                        <div class="subs-purchase text-center pt-2">
                            <span class="secondary-txt">
                                <span class="mdi mdi-boom-gate-up-outline"></span> Validity:<br>
                            </span>
                            1 Month
                        </div>
                    </div>
                    <div class="w-100">
                        <div class="subs-purchase text-center pt-2">
                            <span class="secondary-txt">
                                <span class="mdi mdi-timer-outline"></span> Valid date:<br>
                            </span>
                            {{ valid_from }} ~ {{ valid_till }}
                        </div>
                    </div>
                    <div class="section-child-wrapper border" :class="!disclaimer_check ? 'border-danger' : 'border-success'">
                        <div class="d-flex">
                            <input type="checkbox" class="mt-1 mr-2" v-model="disclaimer_check">
                            <label>By checking this, you understand that this subscription is not refundable.</label>
                        </div>
                    </div>
                    <button class="btn btn-primary d-block w-100 mt-3" :disabled="!disclaimer_check" @click="purchaseSubs()">
                        {{ has_subscription ? 'Extend subscription' : 'Purchase subscription' }}
                    </button>
                </div>
                <hr>
                <h5 class="text-danger text-center mt-4 overflow-hidden danger-title" v-if="has_subscription">
                    <span>!! Danger !!</span>
                </h5>
                <div class="terminate-section section-wrapper border border-danger" v-if="has_subscription">
                    <h5 class="section-title text-danger">Terminate my subscription</h5>
                    <p>
                        Before proceeding, we would like to inform you that this action is <strong>irreversible</strong>
                        and all your subscriptions will be terminated.
                    </p>
                    <div class="section-child-wrapper border" :class="!termination_check ? 'border-danger' : 'border-success'">
                        <div class="d-flex">
                            <input type="checkbox" class="mt-1 mr-2" v-model="termination_check">
                            <label>Yes, terminate all my subscriptions.</label>
                        </div>
                    </div>
                    <button class="btn btn-danger d-block w-100 mt-3" :disabled="!termination_check" @click="terminateSubs()">
                        Terminate subscription
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import SubscriptionAvailabilityChart from "../components/charts/SubscriptionAvailability.vue";

    export default {
        components: {
            SubscriptionAvailabilityChart,
        },
        data() {
            return {
                has_subscription: false,
                subscription_availability: 0,
                // subscription_size: 0,
                subscription_state: [],
                estimated_date: '',
                valid_from: '',
                valid_till: '',
                disclaimer_check: false,
                termination_check: false,
            }
        },
        mounted() {
            this.getSubscriptionState();
            this.getSubscriptionAvailability();
            // this.getSubscriptionSize();
        },
        methods: {
            toDateString(_date) {
                return _date.getFullYear() + '-' +
                        ("0" + (_date.getMonth() + 1)).slice(-2) + '-' +
                        ("0" + _date.getDate()).slice(-2);
            },
            getSubscriptionState() {
                axios
                    .get('/subscription/state')
                    .then((result) => {
                        console.log(result.data)
                        if (result.data.hasSubscription) {
                            this.has_subscription = true;
                            this.subscription_state = result.data.data;
                        } else {
                            this.has_subscription = false;
                            this.subscription_state = [];
                        }

                        if (this.has_subscription) {
                            let last_index = this.subscription_state.length - 1;
                            let _date = new Date(this.subscription_state[last_index].valid_till.replace(/-/g, '/'));
                            _date.setDate(_date.getDate() + 1);
                            this.valid_from = this.toDateString(_date);
                            _date.setDate(_date.getDate() + 30);
                            this.valid_till = this.toDateString(_date);
                        } else {
                            let _date = new Date();
                            this.valid_from = this.toDateString(_date);
                            _date.setDate(_date.getDate() + 30);
                            this.valid_till = this.toDateString(_date);
                        }
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
            // getSubscriptionSize() {
            //     axios
            //         .get('/api/subscription/size')
            //         .then((result) => {
            //             this.subscription_size = result.data;
            //         });
            // },
            purchaseSubs() {
                let data = {
                    valid_at: this.valid_from,
                    valid_till: this.valid_till,
                    mode: this.has_subscription ? 'extend' : 'purchase'
                }
                this.$swal({
                    title: 'Purchasing subscription',
                    text: 'Please wait.',
                    icon: 'info',
                    allowOutsideClick: false,
                })
                this.$swal.showLoading();

                axios
                    .post('/subscription/purchase', data)
                    .then((result) => {
                        if (result.data.isSuccess) {
                            this.$swal.fire({
                                title: 'Purchasing Subscription',
                                text: 'Purchase successful',
                                icon: 'success',
                            })
                            this.getSubscriptionState();
                            this.getSubscriptionAvailability();
                            this.getSubscriptionSize();
                            this.disclaimer_check = false;
                            this.$forceUpdate();
                        } else {
                            this.$swal.fire({
                                title: result.data.message,
                                text: `Please top up your APCard at least RM${(result.data.to_pay / 100).toFixed(2)}`,
                                icon: 'error',
                                confirmButtonText: 'Ok'
                            })
                        }
                    });
            },
            terminateSubs() {
                this.$swal({
                    title: 'Terminating subscription',
                    text: 'Please wait.',
                    icon: 'info',
                    allowOutsideClick: false,
                })
                this.$swal.showLoading();

                axios
                    .post('/subscription/terminate')
                    .then((result) => {
                        if (result.status == 200) {
                            this.$swal.fire({
                                title: 'Terminating Subscription',
                                text: 'Teminate successful',
                                icon: 'success',
                            })
                            this.getSubscriptionState();
                            this.getSubscriptionAvailability();
                            this.getSubscriptionSize();
                            this.termination_check = false;
                            this.$forceUpdate();
                        }
                    });
            },
        }
    }
</script>

<style lang="scss">
    @import './resources/sass/_variables.scss';

    .subs-date {
        flex: 1 1 210px;
    }
    .subs-purchase {
        flex: 1 1 110px;
        font-size: 1.2em;
    }
    .danger-title {
        &:before,
        &:after {
            background-color: $danger;
            content: "";
            display: inline-block;
            height: 1px;
            position: relative;
            vertical-align: middle;
            width: 50%;
        }
        &:before {
            right: 0.5em;
            margin-left: -50%;
        }
        &:after {
            left: 0.5em;
            margin-right: -50%;
        }
    }
    .terminate-section {
        background-color: $tertiary-bg !important;
    }
</style>

