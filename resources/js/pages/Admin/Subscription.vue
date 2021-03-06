<template>
    <div class="page">
        <div class="page-header">
            <h1 class="page-title">Subscription</h1>
        </div>
        <div class="page-content">
            <div class="center-container">
                <h1>Season Parking Subscription</h1>
                <div class="section-wrapper">
                    <h5 class="section-title text-center">
                        <span class="mdi mdi-calendar-clock"></span>
                        Subscription Availability
                    </h5>
                    <div class="my-2">
                        <SubscriptionAvailabilityChart :key="chartKey" style="height: 130px;" />
                    </div>
                    <div class="my-2" v-if="subscription_availability == 0">
                        <div class="d-flex flex-wrap justify-content-start mt-2">
                            <div class="subs-restock text-center secondary-txt">
                                Estimated restock date:
                            </div>
                            <div class="subs-restock text-center">
                                {{ estimated_date }}
                            </div>
                        </div>
                    </div>
                    <router-link to="/admin/subscription/active">
                        <button class="btn btn-primary d-block mt-4 w-100">
                            <span class="mdi mdi-list-status"></span> View active subscriptions
                        </button>
                    </router-link>
                </div>

                <h1>Student's subscription</h1>
                <div class="section-wrapper">
                    To check student's subscription, <br>
                    enter the TP number of the student below and click Submit <br>
                    <span class="text-muted">*Case insensitive</span>
                    <input
                        type="text" class="form-control mt-2" placeholder="TP012345"
                        maxlength="8" v-model="student_id"
                        :class="(invalid_id) ? 'border border-danger input-error' : null"
                    >
                    <span class="text-danger" v-if="invalid_id">Invalid student ID</span>
                    <button class="btn btn-primary d-block mt-4 w-100"
                    :disabled="student_id.length != 8 || invalid_id" @click="getStudentSubs()">
                        <span class="mdi mdi-list-status"></span> Submit
                    </button>
                </div>
                <div class="section-wrapper" v-if="has_profile && (subscription_availability > 0 || has_subscription)">
                    <h5 class="section-title">
                        {{ has_subscription ? 'Extend subscription' : 'Purchase subscription' }}
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
                        <input type="checkbox" class="mr-2" v-model="disclaimer_check">
                        <label>By checking this, you understand that this subscription is not refundable.</label>
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
                    <h5 class="section-title text-danger">Terminate subscription</h5>
                    <p>
                        Before proceeding, we would like to inform you that this action is <strong>irreversible</strong>
                        and all the student's subscriptions will be terminated.
                    </p>
                    <div class="section-child-wrapper border" :class="!termination_check ? 'border-danger' : 'border-success'">
                        <input type="checkbox" class="mr-2" v-model="termination_check">
                        <label>Yes, terminate student's subscriptions.</label>
                    </div>
                    <button class="btn btn-danger d-block w-100 mt-3" :disabled="!termination_check" @click="terminateSubs()">
                        Terminate subscription
                    </button>
                </div>
                <hr>
                <h3>Owned subscription</h3>
                <div class="section-wrapper" v-if="!has_profile">
                    Please load subscription data by submitting student's TP number.
                </div>
                <div class="section-wrapper" v-else>
                    {{
                        has_subscription
                        ? 'Student currently has active subscription.'
                        : 'Student doesn\'t have any subscription.'
                    }}
                </div>
                <div class="section-wrapper" v-if="has_subscription">
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
                            <div class="subs-date text-center">
                                <span class="secondary-txt my-1">
                                    <span class="mdi mdi-credit-card-outline"></span> Status:<br>
                                </span>
                                <span :class="sub.is_active ? 'text-success' : 'text-primary'">
                                    {{ sub.is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>
                    </div>
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
                student_id: '',
                stored_id: '',      // for static id that does not change
                has_profile: false,
                has_subscription: false,
                subscription_availability: 0,
                subscription_size: 0,
                subscription_state: [],
                estimated_date: '',
                valid_from: '',
                valid_till: '',
                disclaimer_check: false,
                termination_check: false,
                chartKey: 0,
            }
        },
        computed: {
            invalid_id() {
                if (this.student_id.length != 8) {
                    return false;
                }
                const regex = /TP[0-9]{6}/ig;
                let match = this.student_id.match(regex);
                return (match) ? false : true;
            }
        },
        mounted() {
            this.getSubscriptionAvailability();
            this.getSubscriptionSize();
        },
        methods: {
            toDateString(_date) {
                return _date.getFullYear() + '-' +
                        ("0" + (_date.getMonth() + 1)).slice(-2) + '-' +
                        ("0" + _date.getDate()).slice(-2);
            },
            getStudentSubs(refresh = false) {
                axios
                    .post('/subscription/state', {tp_number: this.student_id})
                    .then((result) => {
                        if (result.data.isSuccess) {
                            if (!refresh) {
                                this.$swal.fire({
                                    title: 'Load subscription',
                                    text: 'Loaded successful',
                                    icon: 'success',
                                })
                            }
                            this.has_profile = true;
                            // json stringify then parse is to remove bind and reactivity from student_id
                            this.stored_id = JSON.parse(JSON.stringify(this.student_id));

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
                        } else {
                            this.$swal.fire({
                                title: 'Load subscription',
                                text: result.data.message,
                                icon: 'error',
                            })
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
            getSubscriptionSize() {
                axios
                    .get('/api/subscription/size')
                    .then((result) => {
                        this.subscription_size = result.data;
                    });
            },
            purchaseSubs() {
                let data = {
                    tp_number: this.stored_id,
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
                            .then(() => {
                                this.getStudentSubs(true);
                                this.getSubscriptionAvailability();
                                this.getSubscriptionSize();
                                this.disclaimer_check = false;
                                this.$forceUpdate();
                                this.chartKey = !this.chartKey;
                            })
                        } else {
                            this.$swal.fire({
                                title: result.data.message,
                                text: 'Oh no, something went wrong.',
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
                    .post('/subscription/terminate', {tp_number: this.stored_id})
                    .then((result) => {
                        if (result.status == 200) {
                            this.$swal.fire({
                                title: 'Terminating Subscription',
                                text: 'Teminate successful',
                                icon: 'success',
                            })
                            .then(() => {
                                this.getStudentSubs(true);
                                this.getSubscriptionAvailability();
                                this.getSubscriptionSize();
                                this.termination_check = false;
                                this.$forceUpdate();
                                this.chartKey = !this.chartKey;
                            })
                        } else {
                            this.$swal.fire({
                                title: result.data.message,
                                text: 'Oh no, something went wrong.',
                                icon: 'error',
                                confirmButtonText: 'Ok'
                            })
                        }
                    });
            },
        }
    }
</script>

<style lang="scss">
    @import './resources/sass/_variables.scss';

    .form-control::-webkit-input-placeholder { /* Chrome/Opera/Safari */
        color: $secondary-txt;
    }
    .form-control::-moz-placeholder { /* Firefox 19+ */
        color: $secondary-txt;
    }
    .form-control:-ms-input-placeholder { /* IE 10+ */
        color: $secondary-txt;
    }
    .form-control:-moz-placeholder { /* Firefox 18- */
        color: $secondary-txt;
    }
    .form-control {
        transition: 0.3s;
    }

    .input-error {
        background-color: rgba($danger, 0.1);
        color: $main-txt;
    }
    .input-error:focus {
        background-color: rgba($danger, 0.5) !important;
        color: $main-txt;
    }

    .terminate-section {
        background-color: $tertiary-bg !important;
    }
</style>

