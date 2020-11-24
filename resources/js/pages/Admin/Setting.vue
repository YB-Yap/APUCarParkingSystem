<template>
    <div class="page">
        <div class="page-header">
            <h1 class="page-title">Setting</h1>
        </div>
        <div class="page-content">
            <div class="center-container">
                <h1>Subscription config</h1>
                <div class="section-wrapper">
                    <div class="form-group">
                        <label for="subsPrice">Price *</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                            <div class="input-group-text">RM</div>
                            </div>
                            <vue-autonumeric
                                v-model="config.subs_price"
                                :options="{
                                    minimumValue: '0',
                                    maximumValue: '100000',
                                    emptyInputBehavior: 'null'
                                }"
                                class="form-control" id="subsPrice"
                                placeholder="60.00" aria-describedby="subsPriceHelp"
                                :class="(config.subs_price == null) ? 'border border-danger input-error' : null"
                            ></vue-autonumeric>
                        </div>
                        <small id="subsPriceHelp" class="form-text text-muted">
                            Subscription price must only contain numbers and 2 decimal places.<br>
                            The value should between 0.00 ~ 100,000.00
                        </small>
                    </div>
                    <div class="form-group">
                        <label for="subsCapacity">Capacity *</label>
                        <vue-autonumeric
                            v-model="config.subs_capacity"
                            :options="{
                                minimumValue: '0',
                                maximumValue: '100000',
                                decimalPlaces: '0',
                                emptyInputBehavior: 'null'
                            }"
                            class="form-control" id="subsCapacity"
                            placeholder="10" aria-describedby="subsCapacityHelp"
                            :class="(config.subs_capacity == null) ? 'border border-danger input-error' : null"
                        ></vue-autonumeric>
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
                        <label for="zoneASize">Car Park Zone A Size *</label>
                        <vue-autonumeric
                            v-model="config.zone_a_size"
                            :options="{
                                minimumValue: '0',
                                maximumValue: '100000',
                                decimalPlaces: '0',
                                emptyInputBehavior: 'null'
                            }"
                            class="form-control" id="zoneASize"
                            placeholder="10" aria-describedby="zoneASizeHelp"
                            :class="(config.zone_a_size == null) ? 'border border-danger input-error' : null"
                        ></vue-autonumeric>
                        <small id="zoneASizeHelp" class="form-text text-muted">
                            Car park size is the total amount of car park space available.<br>
                            The value should between 0 ~ 100,000
                        </small>
                    </div>
                    <div class="form-group">
                        <label for="zoneBSize">Car Park Zone B Size *</label>
                        <vue-autonumeric
                            v-model="config.zone_b_size"
                            :options="{
                                minimumValue: '0',
                                maximumValue: '100000',
                                decimalPlaces: '0',
                                emptyInputBehavior: 'null'
                            }"
                            class="form-control" id="zoneBSize"
                            placeholder="10" aria-describedby="zoneBSizeHelp"
                            :class="(config.zone_b_size == null) ? 'border border-danger input-error' : null"
                        ></vue-autonumeric>
                        <small id="zoneBSizeHelp" class="form-text text-muted">
                            Car park size is the total amount of car park space available.<br>
                            The value should between 0 ~ 100,000
                        </small>
                    </div>
                    <hr>
                    <span class="text-muted">* Required</span>
                </div>
                <button
                    class="btn btn-primary mt-4 w-100"
                    :disabled="invalid_form"
                >Update</button>
            </div>
        </div>
    </div>
</template>

<script>
    import VueAutonumeric from 'vue-autonumeric';

    export default {
        data() {
            return {
                config: {
                    subs_price: 0.00,
                    subs_capacity: 0,
                    zone_a_size: 0,
                    zone_b_size: 0,
                },
            }
        },
        components: {
            VueAutonumeric,
        },
        computed: {
            invalid_form: function() {
                return ((this.config.subs_price != null) &&
                    (this.config.subs_capacity != null) &&
                    (this.config.zone_a_size != null) &&
                    (this.config.zone_b_size != null)) ? false : true;
            }
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
    }
    .input-error:focus {
        background-color: rgba($danger, 0.5) !important;
    }
</style>
