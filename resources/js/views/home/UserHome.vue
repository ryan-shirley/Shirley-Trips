<template>
    <div v-cloak>
        <navigation></navigation>

        <section class="bg-primary page-title">
            <h1>Good Morning, {{ user.first_name }}!</h1>
            <p>{{ inspiration }}</p>
        </section>

        <section class="container" v-if="holidays.length != 0">
            <h2 class="mb-3">Your Last Holidays</h2>

            <router-link class="holiday" :to="{ name:'holiday.view', params: { 'holidayId' :holiday.id }}" v-for="holiday in holidays" :key="holiday.id">
                <div class="card" :style="{ 'background-image': 'url(' + holiday.image.path + ')' }">
                    <div class="overlay">
                        <h2 class="title">{{ holiday.title }}</h2>
                        <p class="subTitle">{{ holiday.subTitle }}</p>
                        <p class="date">{{ holiday.beginDate.slice(-2) }} {{ month_name(new Date(holiday.beginDate)) }} - {{ holiday.endDate.slice(-2) }} {{ month_name(new Date(holiday.endDate)) }}</p>
                    </div>
                </div>
            </router-link>

        </section>
        <section v-else>
            <h2>Oops looks like you have no holidays yet. Why not try creating one?</h2>
        </section>
    </div>
</template>

<script>
    export default {
        mounted() {
            let app = this
            let token = localStorage.getItem('token')

            app.user.first_name = localStorage.getItem('first_name')
            app.user.isAdmin = localStorage.getItem('isAdmin')

            axios.get('/api/inspiration', {
                headers: { Authorization: "Bearer " + token }
            })
            .then(function (resp) {
                app.inspiration = resp.data;
            })
            .catch(function (resp) {
                alert('Could not load inspiration')
            })

            axios.get('/api/holiday', {
                headers: { Authorization: "Bearer " + token }
            })
            .then(function (resp) {
                app.holidays = resp.data;
            })
            .catch(function (resp) {
                alert('Could not load holidays')
            })
        },
        data() {
            return {
                user: {
                    first_name: '',
                    isAdmin: false
                },
                inspiration: '',
                holidays: []
            }
        },
        methods: {
            logout() {
                let app = this
                let token = localStorage.removeItem('token')
                app.$router.push({ name: 'login' })
            },
            month_name(dt){
                let mlist = [ "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December" ];
                return mlist[dt.getMonth()];
            }
        }
    }
</script>
