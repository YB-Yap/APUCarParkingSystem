<template>
    <div class="page">
        <div class="page-header">
            <h1 class="page-title">More</h1>
        </div>
        <div class="page-content">
            <ul class="list-group">
                <li class="list-group-item">
                    <router-link to="dashboard" class="more-link">
                        <span class="more-icon mdi mdi-home"> Dashboard</span>
                    </router-link>
                </li>
                <li class="list-group-item">
                    <router-link to="parking-status" class="more-link">
                        <span class="more-icon mdi mdi-parking"> Parking Status</span>
                    </router-link>
                </li>
                <li class="list-group-item">
                    <router-link :to="{name: 'subscription', params: {user_id: user_id}}" class="more-link">
                        <span class="more-icon mdi mdi-calendar-clock"> Subscription</span>
                    </router-link>
                </li>
                <li class="list-group-item">
                    <router-link :to="{name: 'simulator', params: {user_id: user_id}}" class="more-link">
                        <span class="more-icon mdi mdi-alert-circle"> **Simulator**</span>
                    </router-link>
                </li>
                <li class="list-group-item">
                    <a class="more-link" @click="logout()">
                        <span class="more-icon mdi mdi-logout"> Logout</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['user_id'],
        methods: {
            logout() {
                this.$swal.fire({
                    text: 'Are you sure you want to log out?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Logout',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    // clicked 'Logout'
                    if (result.value) {
                        window.location.href = '/logout'
                    }
                })
            }
        }
    }
</script>

<style lang="scss">
    @import './resources/sass/_variables.scss';

    .list-group-item {
        background-color: $tertiary-bg;
        padding: 15px 30px;
        border: 1px solid #00000055;
    }

    .more-icon {
        display: inline-block;
        font-size: 24px;
        height: 38px;
    }

    .more-link {
        color: $tertiary-txt;
        text-overflow: ellipsis;
        white-space: nowrap;
        overflow: hidden;
        text-decoration: none;
        cursor: pointer;

        &:hover {
            color: $blue;
        }
    }
</style>
