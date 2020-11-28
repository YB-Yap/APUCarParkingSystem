<template>
    <div class="page">
        <div class="page-header">
            <h1 class="page-title">Subscription History</h1>
        </div>
        <div class="page-content">
            <div class="center-container">
                <h1>Subscription history</h1>
                <div class="section-wrapper" v-if="subscription_records.length == 0">
                    No records.
                </div>
                <div class="section-wrapper" v-else>
                    <div class="section-child-wrapper" v-for="(record, index) in subscription_records" :key=index>
                        <h5 :class="`text-${record.style}`">{{ record.description }}</h5>
                        Amount: {{
                            `${(record.type == 'deduct') ? `-` : `+`} RM${(record.amount / 100).toFixed(2)}`
                        }}<br>
                        <span class="mdi mdi-clock-outline"></span> Timestamp:
                        {{ `${record.created_at.date}, ${record.created_at.weekday} at ${record.created_at.time}` }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                subscription_records: [],
            }
        },
        mounted() {
            this.getSubscriptionRecords();
        },
        methods: {
            toDateString(_date) {
                return _date.getFullYear() + '-' +
                        ("0" + (_date.getMonth() + 1)).slice(-2) + '-' +
                        ("0" + _date.getDate()).slice(-2);
            },
            toTimeString(_date) {
                return ("0" + _date.getHours()).slice(-2) + ':' +
                        ("0" + _date.getMinutes()).slice(-2) + ':' +
                        ("0" + _date.getSeconds()).slice(-2);
            },
            getWeekDay(_date){
                let weekdays = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
                return weekdays[_date.getDay()];
            },
            getSubscriptionRecords() {
                axios
                    .get('/transaction/subscription-records')
                    .then((result) => {
                        // console.log(result.data.data);
                        // this.subscription_records = result.data.data;
                        this.subscription_records = _.map(result.data.data, record => {
                            if (record.description.toLowerCase().includes('purchase')) {
                                record.style = 'success';
                            } else if (record.description.toLowerCase().includes('extend')) {
                                record.style = 'info';
                            } else if (record.description.toLowerCase().includes('terminate')) {
                                record.style = 'danger';
                            }

                            let _date = new Date(record.created_at);
                            record.created_at = {
                                date: this.toDateString(_date),
                                weekday: this.getWeekDay(_date),
                                time: this.toTimeString(_date)
                            };
                            return record;
                        });
                    });
            }
        }
    }
</script>

<style lang="scss">
    @import './resources/sass/_variables.scss';

</style>
