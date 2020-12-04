<script>
    import { HorizontalBar } from "vue-chartjs";

    export default {
        extends: HorizontalBar,
        data() {
            return {
                subscription_availability: 0,
                subscription_size: 0,
                temp: {
                    availability: false,
                    size: false
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        xAxes: [{
                            stacked: true
                        }],
                        yAxes: [{
                            stacked: true,
                            ticks: {
                                fontSize: 0
                            }
                        }]
                    },
                },
            }
        },
        mounted() {
            this.getSubscriptionAvailability();
            this.getSubscriptionSize();
        },
        methods: {
            render() {
                var sold = [
                    this.subscription_size - this.subscription_availability
                ];
                var available = [this.subscription_availability];

                this.renderChart({
                    labels: ["Season Parking Subscription"],
                    datasets: [
                        {
                            label: "Occupied",
                            backgroundColor: "#e54d4233",
                            borderColor: "#b02d2355",
                            borderWidth: "3",
                            data: sold
                        },
                        {
                            label: "Available",
                            backgroundColor: "#49b57133",
                            borderColor: "#2d8c5055",
                            borderWidth: "3",
                            data: available
                        }
                    ]},
                    this.options
                );
            },
            getSubscriptionAvailability() {
                axios
                    .get('/api/subscription/availability')
                    .then((result) => {
                        this.subscription_availability = result.data;
                        this.temp.availability = true;
                        if (this.temp.availability && this.temp.size) {
                            this.render();
                        }
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
                        this.temp.size = true;
                        if (this.temp.availability && this.temp.size) {
                            this.render();
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
        },
    }
</script>
