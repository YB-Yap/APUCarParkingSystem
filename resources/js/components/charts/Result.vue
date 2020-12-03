<script>
    import { HorizontalBar } from "vue-chartjs";

    export default {
        extends: HorizontalBar,
        data() {
            return {
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        xAxes: [{
                            stacked: true
                        }],
                        yAxes: [{
                            stacked: true
                        }]
                    }
                },
            }
        },
        mounted() {
            this.renderChart({
                labels: ["Parking availability"],
                datasets: [
                    {
                        label: "Occupied",
                        backgroundColor: "#e54d4233",
                        borderColor: "#b02d2355",
                        borderWidth: "3",
                        data: [8]
                    },
                    {
                        label: "Available",
                        backgroundColor: "#49b57133",
                        borderColor: "#2d8c5055",
                        borderWidth: "3",
                        data: [10]
                    }
                ]},
                this.options
            );
        }
    }
</script>
