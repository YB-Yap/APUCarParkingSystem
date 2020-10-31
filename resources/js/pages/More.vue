<template>
    <div class="links-wrapper">
        <ul class="list-group">
            <li class="list-group-item">
                <router-link to="/dashboard" class="more-link">
                    <span class="more-icon mdi mdi-home"> Dashboard</span>
                </router-link>
            </li>
            <li class="list-group-item">
                <router-link to="parking-status" class="more-link">
                    <span class="more-icon mdi mdi-eye"> Parking Status</span>
                </router-link>
            </li>
            <li class="list-group-item">
                <router-link to="car-park-status" class="more-link">
                    <span class="more-icon mdi mdi-parking"> Car Park Status</span>
                </router-link>
            </li>
            <li class="list-group-item">
                <router-link to="parking-gate" class="more-link">
                    <span class="more-icon mdi mdi-alert-circle"> Parking Gate **Simulator**</span>
                </router-link>
            </li>
            <li class="list-group-item">
                <a class="more-link" @click="logout()">
                    <span class="more-icon mdi mdi-logout"> Logout</span>
                </a>
            </li>
        </ul>
    </div>
</template>

<script>
export default {

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
