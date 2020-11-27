<template>
    <div class="page">
        <div class="page-header">
            <h1 class="page-title">Subscription</h1>
        </div>
        <div class="page-content">
            <div class="center-container">
                <h1>Season Parking Subscription</h1>
                <div class="section-wrapper">
                    Availability: {{ subscription_availability }} of {{ subscription_size }}
                </div>
                <h1>Active Subscription</h1>
                <div class="section-wrapper" v-if="subscription_availability != 0">
                    <hr>
                    <div v-for="(subs, index) in subscription_state" :key="index">
                        <h5>{{ `${subs[0].user.fullname}, ${subs[0].user.tp_number}` }}</h5>
                        <div class="section-child-wrapper">
                            Valid from: {{ subs[0].valid_at }}<br>
                            Valid till: {{ subs[subs.length -1].valid_till }}<br>
                            Duration: {{ subs.length }} month(s)
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
    export default {
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
                        // console.log(result.data);
                        this.subscription_state = _.groupBy(result.data, record => {
                            return record.user_id;
                        });
                        // console.log(this.subscription_state);
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

<style>

</style>