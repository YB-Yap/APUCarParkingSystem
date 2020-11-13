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
                    <span>You don't have any subscription.</span>
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
                        {{ has_subscription ? 'Extend your subscription' : 'Purchase a subscription' }}
                    </h5>
                    <span class="mdi mdi-credit-card-outline"> RM 60.00</span><br>
                    <span class="mdi mdi-timer-outline"> {{ valid_from }} ~ {{ valid_till }}</span><br>
                    <span class="mdi mdi-boom-gate-up-outline"> 1 Month</span>
                    <div class="disclaimer border" :class="!disclaimer_check ? 'border-danger' : 'border-success'">
                        <input type="checkbox" class="mr-2" v-model="disclaimer_check">
                        <label>By checking this, you understand that this subscription is not refundable.</label>
                    </div>
                    <button class="btn btn-primary d-block w-100 mt-3" :disabled="!disclaimer_check" @click="purchaseSubs()">
                        {{ has_subscription ? 'Extend subscription' : 'Purchase subscription' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['user'],
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
            }
        },
        mounted() {
            this.getSubscriptionState();
            this.getSubscriptionAvailability();
            this.getSubscriptionSize();
        },
        methods: {
            getSubscriptionState() {
                axios
                    .get('/subscription/get-state')
                    .then((result) => {
                        console.log(result.data)
                        if (result.data.hasSubscription) {
                            this.has_subscription = true;
                            this.subscription_state = result.data.data;
                        }

                        if (this.has_subscription) {
                            let last_index = this.subscription_state.length - 1;
                            let _date = new Date(this.subscription_state[last_index].valid_till);
                            _date.setDate(_date.getDate() + 1);
                            this.valid_from = _date.getFullYear() + '-' + ("0" + (_date.getMonth() + 1)).slice(-2) + '-' + ("0" + _date.getDate()).slice(-2);
                            _date.setDate(_date.getDate() + 30);
                            this.valid_till = _date.getFullYear() + '-' + ("0" + (_date.getMonth() + 1)).slice(-2) + '-' + ("0" + _date.getDate()).slice(-2);
                        } else {
                            let _date = new Date();
                            this.valid_from = _date.getFullYear() + '-' + ("0" + (_date.getMonth() + 1)).slice(-2) + '-' + ("0" + _date.getDate()).slice(-2);
                            _date.setDate(_date.getDate() + 30);
                            this.valid_till = _date.getFullYear() + '-' + ("0" + (_date.getMonth() + 1)).slice(-2) + '-' + ("0" + _date.getDate()).slice(-2);
                        }
                    });
            },
            getSubscriptionAvailability() {
                axios
                    .get('/api/subscription/availability')
                    .then((result) => {
                        this.subscription_availability = result.data;
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

                axios
                    .post('/subscription/purchase', data)
                    .then((result) => {
                        console.log('buy')
                    });
            },
        }
    }
</script>

<style lang="scss">
    @import './resources/sass/_variables.scss';


</style>

