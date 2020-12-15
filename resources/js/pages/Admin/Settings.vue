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
                        </div>
                        <span class="text-danger" v-if="config.subscription_price == 0">
                            You acknowledge that this field will be set to zero.
                            If you do not want this field to be zero, please input valid value.
                        </span>
                        <span id="subsPriceHelp" class="form-text text-muted">
                            Subscription price must only contain numbers and 2 decimal places.<br>
                            The value should between 0.00 ~ 100,000.00
                        </span>
                    </div>
                    <div class="form-group">
                        <label class="config-label" for="subsCapacity">Capacity *</label>
                        <vue-numeric
                            v-model="config.subscription_quantity"
                            :min="0" :max="100000" :precision="0"
                            class="form-control" id="subsCapacity"
                            placeholder="10" aria-describedby="subsCapacityHelp"
                        ></vue-numeric>
                        <span class="text-danger" v-if="config.subscription_quantity == 0">
                            You acknowledge that this field will be set to zero.
                            If you do not want this field to be zero, please input valid value.
                        </span>
                        <span id="subsCapacityHelp" class="form-text text-muted">
                            Subscription capacity is the total amount of subscription that can be purchased.<br>
                            The value should between 0 ~ 100,000
                        </span>
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
                        <span class="text-danger" v-if="config.zone_a_size == 0">
                            You acknowledge that this field will be set to zero.
                            If you do not want this field to be zero, please input valid value.
                        </span>
                        <span id="zoneASizeHelp" class="form-text text-muted">
                            Car park size is the total amount of car park space available.<br>
                            The value should between 0 ~ 100,000
                        </span>
                    </div>
                    <div class="form-group">
                        <label class="config-label" for="zoneBSize">Car Park Zone B Size *</label>
                        <vue-numeric
                            v-model="config.zone_b_size"
                            :min="0" :max="100000" :precision="0"
                            class="form-control" id="zoneBSize"
                            placeholder="10" aria-describedby="zoneBSizeHelp"
                        ></vue-numeric>
                        <span class="text-danger" v-if="config.zone_b_size == 0">
                            You acknowledge that this field will be set to zero.
                            If you do not want this field to be zero, please input valid value.
                        </span>
                        <span id="zoneBSizeHelp" class="form-text text-muted">
                            Car park size is the total amount of car park space available.<br>
                            The value should between 0 ~ 100,000
                        </span>
                    </div>
                    <hr>
                    <span class="text-muted">* Required</span>
                </div>
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
            VueNumeric
        },
        mounted() {
            this.getConfig();
        },
        methods: {
            getConfig() {
                axios
                    .get('/api/admin/config')
                    .then((result) => {
                        $.each(result.data, (config_key, config_value) => {
                            if (config_value.key == 'subscription_price') {
                                this.config[config_value.key] = (config_value.value / 100).toFixed(2);
                            } else {
                                this.config[config_value.key] = config_value.value;
                            }
                        });
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
