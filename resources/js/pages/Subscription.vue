<template>
    <div class="page">
        <div class="page-header">
            <h1 class="page-title">Subscription</h1>
        </div>
        <div class="page-content">
            <h1>Subscription status</h1>
            <div v-if="has_subscription">
                <span>Your subscription is currently active.</span>
                <div class="card mb-2" v-for="(sub, index) in subscription_state" :key="index">
                    <div class="card-body" :class="sub.is_active ? 'text-success' : 'text-info'">
                        Valid from: {{ sub.valid_at }}<br>
                        Valid till: {{ sub.valid_till }}<br>
                        Status: {{ sub.is_active ? 'Active' : 'Inactive' }}<br>
                    </div>
                </div>
            </div>
            <div v-else>
                <span>You don't have any subscription.</span>
            </div>

            <h1>Subscription availability</h1>
            Availability: {{ subscription_availability }} of {{ subscription_size }}
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                has_subscription: false,
                subscription_availability: 0,
                subscription_size: 0,
                subscription_state: [],
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
                    .get('/get-subs-state')
                    .then((result) => {
                        console.log(result.data)
                        if (result.data.hasSubscription) {
                            this.has_subscription = true;
                            this.subscription_state = result.data.data;
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
        }
    }
</script>

<style lang="scss">
    @import './resources/sass/_variables.scss';

</style>

