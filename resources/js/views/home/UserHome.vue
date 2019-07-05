<template>
    <div v-cloak>
        <navigation></navigation>

        <section class="bg-primary page-title">
            <h1>{{ getWelcomeMessage }}</h1>
            <p>{{ inspiration }}</p>
        </section>

        <section class="container" v-if="pastHolidays.length != 0">
            <h2 class="mb-3">Your Past Holidays</h2>

            <div v-for="holiday in pastHolidays" :key="holiday.id">
                <holiday-card :holiday="holiday" />
            </div>
        </section>

        <section class="container" v-if="liveHolidays.length != 0">
            <h2 class="mb-3">Your Live Holidays</h2>

            <div v-for="holiday in liveHolidays" :key="holiday.id">
                <holiday-card :holiday="holiday" />
            </div>
        </section>

        <section class="container" v-if="upcommingHolidays.length != 0">
            <h2 class="mb-3">Your Upcomming Holidays</h2>

            <div v-for="holiday in upcommingHolidays" :key="holiday.id">
                <holiday-card :holiday="holiday" />
            </div>
        </section>

        <section v-else class="container">
            <h2>Oops looks like you have no holidays yet. Why not try creating one?</h2>
        </section>
    </div>
</template>

<script>
    export default {
        mounted() {
            let app = this
            let token = localStorage.getItem('token')

            // Load holidays
            axios.get('/api/holiday', {
                headers: { Authorization: "Bearer " + token }
            })
            .then(function (resp) {
                app.inspiration = resp.data.inspiration
                app.liveHolidays = resp.data.live_holidays
                app.upcommingHolidays = resp.data.upcomming_holidays
                app.pastHolidays = resp.data.past_holidays
                app.user.first_name = resp.data.user_first_name
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
                liveHolidays: [],
                upcommingHolidays: [],
                pastHolidays: []

            }
        },
        methods: {
            logout() {
                let app = this
                let token = localStorage.removeItem('token')
                app.$router.push({ name: 'login' })
            }
        },
        computed: {
            getWelcomeMessage() {
                let today = new Date()
                let curHr = today.getHours()
                let message = ''

                if (curHr < 12) {
                    message = 'Good Morning'
                } else if (curHr < 18) {
                    message = 'Good Afternoon'
                } else {
                    message = 'Good Evening'
                }

                return message + ', ' + this.user.first_name + '!'
            },
            sortedHolidays: function () {
                return _.orderBy(this.holidays, 'beginDate', 'desc')
            }
        }
    }
</script>
