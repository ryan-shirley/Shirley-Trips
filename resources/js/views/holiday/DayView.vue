<template>
    <div class="row justify-content-md-center">
        <div class="col-12 col-sm-6">
            <activity-list 
                v-if="date"
                :activities="activities" 
                :hotel="hotel"
                :day="date"
                :dayId="dayId"
                :reOrderMode="reOrderMode"
                :key="date"
            />

            <div class="container mt-5 pt-5 mb-5">
                <div class="row">
                    <div class="col" v-if="previousDay">
                        <router-link v-if="previousDay.id" class="btn btn-primary btn-block" :to="{ name:'holiday.view.day', params: { 'dayId' :previousDay.id }}">
                            <i class="fas fa-angle-double-left"></i> Previous Day
                        </router-link>
                    </div>
                    <div class="col" v-if="nextDay">
                        <router-link v-if="nextDay.id" class="btn btn-primary btn-block" :to="{ name:'holiday.view.day', params: { 'dayId' :nextDay.id }}">
                            Next Day <i class="fas fa-angle-double-right"></i>
                        </router-link>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script>

    export default {
        props: {
            reOrderMode: Boolean,
        },
        data() {
            return {
                dayId: '',
                date: '',
                activities: [],
                hotel: {},
                previousDay: {},
                nextDay: {}
            }
        },
        mounted() {
            let app = this
            let token = localStorage.getItem('token')
            let id = app.$route.params.dayId

            axios.get('/api/day/' + id, {
                headers: { Authorization: "Bearer " + token }
            })
            .then(function (resp) {
                let data = resp.data.day

                app.activities = data.activities
                app.hotel = data.hotel
                app.dayId = data.dayId
                app.date = data.date
                app.nextDay = resp.data.nextDay
                app.previousDay = resp.data.previousDay
            })
            .catch(function (resp) {
                alert('Could not load day')
            })
        }
    }
</script>
