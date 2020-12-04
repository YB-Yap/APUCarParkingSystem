<script>
    import { HorizontalBar } from "vue-chartjs";

    export default {
        extends: HorizontalBar,
        data() {
            return {
                parking_availability: {
                    zone_a: 0,
                    zone_b: 0,
                },
                parking_size: {
                    zone_a: 0,
                    zone_b: 0,
                },
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
                                fontSize: 12
                            }
                        }]
                    },
                },
            }
        },
        mounted() {
            this.getCarParkAvailability();
            this.getCarParkSize();
        },
        methods: {
            render() {
                var occupied = [
                    this.parking_size.zone_a - this.parking_availability.zone_a,
                    this.parking_size.zone_b - this.parking_availability.zone_b
                ];
                var available = [
                    this.parking_availability.zone_a,
                    this.parking_availability.zone_b
                ];

                this.renderChart({
                    labels: ["A", "B"],
                    datasets: [
                        {
                            label: "Occupied",
                            backgroundColor: "#e54d4233",
                            borderColor: "#b02d2355",
                            borderWidth: "3",
                            data: occupied
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
            getCarParkAvailability() {
                axios
                    .get('/api/parking/availability')
                    .then((result) => {
                        this.parking_availability = result.data;
                        this.temp.availability = true;
                        if (this.temp.availability && this.temp.size) {
                            this.render();
                        }
                    });
            },
            getCarParkSize() {
                axios
                    .get('/api/parking/size')
                    .then((result) => {
                        this.parking_size = result.data;
                        this.temp.size = true;
                        if (this.temp.availability && this.temp.size) {
                            this.render();
                        }
                    })
            },
        },
    }
</script>
