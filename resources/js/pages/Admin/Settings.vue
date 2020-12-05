<template>
    <div class="page">
        <div class="page-header">
            <h1 class="page-title">Settings</h1>
        </div>
        <div class="page-content">
            <div class="center-container">
                <h1>Subscription config</h1>
                <div class="section-wrapper">
                    <div class="form-group">
                        <label class="config-label" for="subsPrice">Price *</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                            <div class="input-group-text">RM</div>
                            </div>
                            <vue-numeric
                                v-model="config.subscription_price"
                                :min="0.00" :max="100000.00" :precision="2"
                                class="form-control" id="subsPrice"
                                placeholder="60.00" aria-describedby="subsPriceHelp"
                            ></vue-numeric>
                                <!-- :class="(config.subscription_price == '') ? 'border border-danger input-error' : null" -->
                            <!-- <input
                                v-model="config.subscription_price"
                                class="form-control" id="subsPrice"
                                placeholder="60.00" aria-describedby="subsPriceHelp"
                                :class="(config.subscription_price == null) ? 'border border-danger input-error' : null"
                            /> -->
                        </div>
                        <small id="subsPriceHelp" class="form-text text-muted">
                            Subscription price must only contain numbers and 2 decimal places.<br>
                            The value should between 0.00 ~ 100,000.00
                        </small>
                    </div>
                    <div class="form-group">
                        <label class="config-label" for="subsCapacity">Capacity *</label>
                        <vue-numeric
                            v-model="config.subscription_quantity"
                            :min="0" :max="100000" :precision="0"
                            class="form-control" id="subsCapacity"
                            placeholder="10" aria-describedby="subsCapacityHelp"
                        ></vue-numeric>
                            <!-- :class="(config.subscription_quantity == '') ? 'border border-danger input-error' : null" -->
                        <!-- <input
                            v-model="config.subscription_quantity"
                            class="form-control" id="subsCapacity"
                            placeholder="10" aria-describedby="subsCapacityHelp"
                            :class="(config.subscription_quantity == null) ? 'border border-danger input-error' : null"
                        /> -->
                        <small id="subsCapacityHelp" class="form-text text-muted">
                            Subscription capacity is the total amount of subscription that can be purchased.<br>
                            The value should between 0 ~ 100,000
                        </small>
                    </div>
                    <hr>
                    <span class="text-muted">* Required</span>
                </div>
                <h1>** Simulator config **</h1>
                <div class="section-wrapper">
                    <div class="form-group">
                        <label class="config-label" for="zoneASize">Car Park Zone A Size *</label>
                        <vue-numeric
                            v-model="config.zone_a_size"
                            :min="0" :max="100000" :precision="0"
                            class="form-control" id="zoneASize"
                            placeholder="10" aria-describedby="zoneASizeHelp"
                        ></vue-numeric>
                            <!-- :class="(config.zone_a_size == '') ? 'border border-danger input-error' : null" -->
                        <!-- <input
                            v-model="config.zone_a_size"
                            class="form-control" id="zoneASize"
                            placeholder="10" aria-describedby="zoneASizeHelp"
                            :class="(config.zone_a_size == null) ? 'border border-danger input-error' : null"
                        /> -->
                        <small id="zoneASizeHelp" class="form-text text-muted">
                            Car park size is the total amount of car park space available.<br>
                            The value should between 0 ~ 100,000
                        </small>
                    </div>
                    <div class="form-group">
                        <label class="config-label" for="zoneBSize">Car Park Zone B Size *</label>
                        <vue-numeric
                            v-model="config.zone_b_size"
                            :min="0" :max="100000" :precision="0"
                            class="form-control" id="zoneBSize"
                            placeholder="10" aria-describedby="zoneBSizeHelp"
                        ></vue-numeric>
                            <!-- :class="(config.zone_b_size == '') ? 'border border-danger input-error' : null" -->
                        <!-- <input
                            v-model="config.zone_b_size"
                            class="form-control" id="zoneBSize"
                            placeholder="10" aria-describedby="zoneBSizeHelp"
                            :class="(config.zone_b_size == null) ? 'border border-danger input-error' : null"
                        /> -->
                        <small id="zoneBSizeHelp" class="form-text text-muted">
                            Car park size is the total amount of car park space available.<br>
                            The value should between 0 ~ 100,000
                        </small>
                    </div>
                    <hr>
                    <span class="text-muted">* Required</span>
                </div>
                    <!-- :disabled="invalid_form" -->
                <button
                    class="btn btn-primary mt-4 w-100"
                    @click="updateConfig()"
                >Update</button>
            </div>
        </div>
    </div>
</template>

<script>
    import VueNumeric from 'vue-numeric';
    // import AutoNumeric from 'autonumeric';
    // import VueAutonumeric from 'vue-autonumeric';

    export default {
        data() {
            return {
                config: {
                    subscription_price: 0.00,
                    subscription_quantity: 0,
                    zone_a_size: 0,
                    zone_b_size: 0,
                },
            }
        },
        components: {
        //     // AutoNumeric,
        //     // VueAutonumeric,
            VueNumeric
        },
        // computed: {
        //     invalid_form: function() {
        //         return ((this.config.subscription_price != '') &&
        //             (this.config.subscription_quantity != '') &&
        //             (this.config.zone_a_size != '') &&
        //             (this.config.zone_b_size != '')) ? false : true;
        //     }
        // },
        mounted() {
            this.getConfig();
            // new AutoNumeric('#subsPrice', {
            //                         minimumValue: '0',
            //                         maximumValue: '100000',
            //                         unformatOnSubmit: true,
            //                         emptyInputBehavior: 'null'
            //                     });
            // new AutoNumeric('#subsCapacity', {
            //                         minimumValue: '0',
            //                         maximumValue: '100000',
            //                         unformatOnSubmit: true,
            //                         decimalPlaces: '0',
            //                         emptyInputBehavior: 'null'
            //                     });
            // new AutoNumeric('#zoneASize', {
            //                         minimumValue: '0',
            //                         maximumValue: '100000',
            //                         unformatOnSubmit: true,
            //                         decimalPlaces: '0',
            //                         emptyInputBehavior: 'null'
            //                     });
            // new AutoNumeric('#zoneBSize', {
            //                         minimumValue: '0',
            //                         maximumValue: '100000',
            //                         unformatOnSubmit: true,
            //                         decimalPlaces: '0',
            //                         emptyInputBehavior: 'null'
            //                     });
        },
        methods: {
            getConfig() {
                axios
                    .get('/api/admin/config')
                    .then((result) => {
                        console.log(result.data);
                        $.each(result.data, (config_key, config_value) => {
                            if (config_value.key == 'subscription_price') {
                                this.config[config_value.key] = (config_value.value / 100).toFixed(2);
                            } else {
                                this.config[config_value.key] = config_value.value;
                            }
                        });
                        // AutoNumeric.set('#subsPrice', this.config.subscription_price)
                        // AutoNumeric.set('#subsCapacity', this.config.subscription_quantity)
                        // AutoNumeric.set('#zoneASize', this.config.zone_a_size)
                        // AutoNumeric.set('#zoneBSize', this.config.zone_b_size)
                    });
            },
            updateConfig() {
                this.$swal({
                    title: 'Update config',
                    text: 'Please wait while the system is updating config.',
                    icon: 'info',
                    allowOutsideClick: false,
                })
                this.$swal.showLoading();
                axios
                    .post(`/api/admin/config/update`, this.config)
                    .then((result) => {
                        if (result.status == 200) {
                            this.$swal.fire({
                                title: 'Update config',
                                text: 'Config successful',
                                icon: 'success',
                            })
                        }
                    });
            }
        }
    }
</script>

<style lang="scss">
    @import './resources/sass/_variables.scss';

    .form-control::-webkit-input-placeholder { /* Chrome/Opera/Safari */
        color: $main-txt;
    }
    .form-control::-moz-placeholder { /* Firefox 19+ */
        color: $main-txt;
    }
    .form-control:-ms-input-placeholder { /* IE 10+ */
        color: $main-txt;
    }
    .form-control:-moz-placeholder { /* Firefox 18- */
        color: $main-txt;
    }
    .form-control {
        transition: 0.3s;
        font-size: 1.2em;
    }

    .config-label {
        font-size: 1.2em;
    }

    .input-error {
        background-color: rgba($danger, 0.1);
    }
    .input-error:focus {
        background-color: rgba($danger, 0.5) !important;
    }
</style>
