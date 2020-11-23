<template>
    <div class="page">
        <div class="page-header">
            <h1 class="page-title">Subscription</h1>
        </div>
        <div class="page-content">
            <div class="center-container">
                <h1>Subscription status</h1>
                <div v-if="has_subscription">
                    <div class="section-wrapper">
                        Your subscription is currently active.
                    </div>
                    <div class="section-wrapper" v-for="(sub, index) in subscription_state" :key="index">
                        <div :class="sub.is_active ? 'text-success' : 'text-info'">
                            Valid from: {{ sub.valid_at }}<br>
                            Valid till: {{ sub.valid_till }}<br>
                            Status: {{ sub.is_active ? 'Active' : 'Inactive' }}<br>
                        </div>
                    </div>
                </div>
                <div v-else>
                    <div class="section-wrapper">
                        You don't have any subscription.
                    </div>
                </div>

                <h1>Season Parking Subscription</h1>
                <div class="section-wrapper" v-if="!has_subscription">
                    Availability: {{ subscription_availability }} of {{ subscription_size }}
                </div>
                <div class="section-wrapper" v-if="subscription_availability == 0 && has_subscription == false">
                    <span>Sorry, there are no subscription available at the moment.</span><br>
                    <span>Estimated restock date: {{ estimated_date }}</span>
                </div>
                <div class="section-wrapper" v-else>
                    <h5 class="section-title">
                        {{ has_subscription ? 'Extend my subscription' : 'Purchase a subscription' }}
                    </h5>
                    <span class="mdi mdi-credit-card-outline"> RM 60.00</span><br>
                    <span class="mdi mdi-timer-outline"> {{ valid_from }} ~ {{ valid_till }}</span><br>
                    <span class="mdi mdi-boom-gate-up-outline"> 1 Month</span>
                    <div class="section-child-wrapper border" :class="!disclaimer_check ? 'border-danger' : 'border-success'">
                        <input type="checkbox" class="mr-2" v-model="disclaimer_check">
                        <label>By checking this, you understand that this subscription is not refundable.</label>
                    </div>
                    <button class="btn btn-primary d-block w-100 mt-3" :disabled="!disclaimer_check" @click="purchaseSubs()">
                        {{ has_subscription ? 'Extend subscription' : 'Purchase subscription' }}
                    </button>
                </div>
                <hr>
                <h5 class="text-danger text-center mt-4" v-if="has_subscription">** Danger **</h5>
                <div class="terminate-section section-wrapper border border-danger" v-if="has_subscription">
                    <h5 class="section-title">Terminate my subscription</h5>
                    <p>
                        Before proceeding, we would like to inform you that this action is <strong>irreversible</strong>
                        and all your subscriptions will be terminated.
                    </p>
                    <div class="section-child-wrapper border" :class="!termination_check ? 'border-danger' : 'border-success'">
                        <input type="checkbox" class="mr-2" v-model="termination_check">
                        <label>Yes, terminate all my subscriptions.</label>
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
    export default {
        props: ['user_id'],
        data() {
            return {
                has_subscription: false,
                subscription_availability: 0,
                subscription_size: 0,
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
            this.getSubscriptionSize();
        },
        methods: {
            toDateString(_date) {
                return _date.getFullYear() + '-' +
                        ("0" + (_date.getMonth() + 1)).slice(-2) + '-' +
                        ("0" + _date.getDate()).slice(-2);
            },
            getSubscriptionState() {
                axios
                    .get('/subscription/get-state')
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
                            let _date = new Date(this.subscription_state[last_index].valid_till);
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
                        this.estimated_date = result.data.estimated_date;
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

    .terminate-section {
        background-color: $tertiary-bg !important;
    }
</style>

