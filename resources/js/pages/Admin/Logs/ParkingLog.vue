<template>
    <div class="page">
        <div class="page-header">
            <h1 class="page-title">View Parking Log</h1>
        </div>
        <div class="page-content">
            <div class="container">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><span @click="goTo()">View Logs</span></li>
                        <li class="breadcrumb-item active" aria-current="page">Parking Log</li>
                    </ol>
                </nav>
                <pagination :data="parking_logs" :show-disabled=true align="right" @pagination-change-page="getParkingLog">
                    <span slot="prev-nav"><span class="mdi mdi-chevron-left"></span></span>
	                <span slot="next-nav"><span class="mdi mdi-chevron-right"></span></span>
                </pagination>
                <div class="table-responsive">
                    <table class="table table-dark table-striped table-hover">
                        <caption>
                            {{ `Currently showing page ${parking_logs.current_page}
                            of ${parking_logs.last_page} page(s)` }}
                        </caption>
                        <thead class="thead-dark">
                            <tr>
                            <th scope="col">#</th>
                            <th scope="col">TP Number</th>
                            <th scope="col">Name</th>
                            <th scope="col" class="small-column text-center">Parking Zone</th>
                            <th scope="col">Time In</th>
                            <th scope="col">Time Out</th>
                            <th scope="col">Duration</th>
                            <th scope="col">Fee<br>(RM)</th>
                            <th scope="col" class="small-column text-center">Car Park Entry Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="log in parking_logs.data" :key="log.id">
                                <th scope="row">{{ log.id }}</th>
                                <td>{{ log.user.tp_number }}</td>
                                <td>{{ log.user.fullname }}</td>
                                <td class="text-center">{{ log.parking_zone }}</td>
                                <td>{{ log.time_in }}</td>
                                <td>{{ log.time_out }}</td>
                                <td>{{ `${log.duration.hours}h ${log.duration.minutes}m` }}</td>
                                <td>{{ (log.fee / 100).toFixed(2) }}</td>
                                <td class="text-center">
                                    <span class="mdi"
                                        :class="log.is_car_park_full
                                        ? 'mdi-minus-circle text-warning'
                                        : 'mdi-check-circle-outline text-success'">
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <pagination :data="parking_logs" :show-disabled="true" align="right" @pagination-change-page="getParkingLog">
                    <span slot="prev-nav"><span class="mdi mdi-chevron-left"></span></span>
	                <span slot="next-nav"><span class="mdi mdi-chevron-right"></span></span>
                </pagination>
                <div class="section-wrapper">
                    Legends: <br>
                    <span class="mdi mdi-minus-circle text-warning"></span> - Car park is full<br>
                    <span class="mdi mdi-check-circle-outline text-success"></span> - Car park is not full
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                parking_logs: {},
            }
        },
        mounted() {
            this.getParkingLog();
        },
        methods: {
            goTo() {
                this.$router.push('/admin/logs');
            },
            toTimeString(_date) {
                return ("0" + _date.getHours()).slice(-2) + ':' +
                        ("0" + _date.getMinutes()).slice(-2) + ':' +
                        ("0" + _date.getSeconds()).slice(-2);
            },
            getParkingLog(page = 1) {
                axios
                    .get(`/api/admin/parking?page=${page}`)
                    .then((result) => {
                        // console.log(result.data);
                        this.parking_logs = result.data;
                        _.map(this.parking_logs.data, _log => {
                            let _hours = Math.floor(_log.duration);
                            let _minutes = Math.floor((_log.duration - _hours) * 60);
                            _log.duration = {
                                hours: _hours,
                                minutes: _minutes
                            };
                            return _log;
                        });
                    });
            },
        }
    }
</script>

<style lang="scss">
    @import './resources/sass/_variables.scss';

    .breadcrumb {
        background-color: $tertiary-bg;

        .breadcrumb-item {
            color: $blue;

            &:hover {
                text-decoration: underline;
                cursor: pointer;
            }
        }

        .active {
            color: $main-txt;

            &:hover {
                text-decoration: none;
                cursor: default;
            }
        }
    }

    .pagination {
        background-color: $secondary-bg;

        .page-link {
            color: $main-txt;
            border-radius: 0.25rem;
            border-color: $tertiary-bg;
            background-color: $tertiary-bg;
            margin-right: 6px;
            margin-top: 6px;
            margin-bottom: 6px;

            &:hover {
                background-color: $secondary-bg;
                border-color: $blue;
            }
        }

        .disabled .page-link {
            border-color: rgba($tertiary-bg, 0.3);
            background-color: rgba($tertiary-bg, 0.3);
        }
    }

    .table {
        .thead-dark {
            th {
                background-color: $secondary-bg;
                height: 5rem;
            }
        }

        .small-column {
            width: 110px;
        }
    }
</style>
