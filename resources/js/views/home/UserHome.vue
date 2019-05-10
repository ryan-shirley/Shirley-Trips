<template>
    <div>
        <navigation></navigation>

        <section class="bg-primary page-title">
            <h1>Good Morning, {{ user.first_name }}!</h1>
            <p>{{ inspiration }}</p>
        </section>

        <section class="container" v-if="holidays.length != 0">
            <h2 class="mb-3">Your Last Holidays</h2>

            <div class="activity-card" v-for="holiday in holidays" :key="holiday.id">
                <div class="body">
                    <h4>{{ holiday.title }}</h4>
                    {{ holiday.subTitle }}
                    <router-link :to="{ name:'holiday.view', params: { 'holidayId' :holiday.id }}" class="btn btn-primary">View</router-link>
                    <span class="date">
                        <p class="number">{{ holiday.beginDate.slice(-2) }}</p>
                        <p class="month">{{ month_name(new Date(holiday.beginDate)) }}</p>
                    </span>
                </div>
                <img :src="holiday.image.path">
            </div>
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
