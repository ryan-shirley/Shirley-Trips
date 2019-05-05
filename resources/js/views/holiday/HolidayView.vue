<template>
    <div>
        <navigation></navigation>

        <section class="bg-primary page-title">
            <h1>{{ holiday.title }}</h1>
            <p>{{ holiday.subTitle }}</p>
            <p>{{ holiday.beginDate.slice(-2) }} {{ month_name(new Date(holiday.beginDate)) }} - {{ holiday.endDate.slice(-2) }} {{ month_name(new Date(holiday.endDate)) }}</p>
        </section>

        <date-slider :parentData="holiday.days" v-on:childToParent="loadDay"></date-slider>

        <activity-list 
            :activitiesRaw="day.activitiesRaw" 
            :hotel="day.hotel"
            :day="day.day"
            v-if="day.activitiesRaw.length || day.day.length"
            :key="day.day"
        >
        </activity-list>

        
    </div>
</template>

<script>

    export default {
        mounted() {
            let app = this
            let id = app.$route.params.id
            let token = localStorage.getItem('token')

            axios.get('/api/holiday/' + id, {
                headers: { Authorization: "Bearer " + token }
            })
            .then(function (resp) {
                app.holiday = resp.data
                app.editPermission()
            })
            .catch(function (resp) {
                alert('Could not load holiday')
            })
        },
        data() {
            return {
                holiday: {},
                editPer: false,
                day: {
                    day: '',
                    hotel: {
                        name: '',
                        location: '',
                        checkIn: '',
                        checkOut: '',
                    },
                    activitiesRaw: []
                }
            }
        },
        methods: {
            editPermission() {
                console.log('Checking Permissions');
                let app = this
                let users = app.holiday.users

                for (var i = 0; i < users.length ; i++) {
                    if(users[i].pivot.editPermission == true) {
                        app.editPer = true;
                        return;
                    }
                }
            },
            month_name(dt){
                let mlist = [ "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December" ];
                return mlist[dt.getMonth()];
            },
            loadDay (day_id) {
                console.log("Loading day: " + day_id)

                let app = this
                let token = localStorage.getItem('token')
                app.day.hotel = {}
                app.day.activitiesRaw = []

                axios.get('/api/day/' + day_id, {
                    headers: { Authorization: "Bearer " + token }
                })
                .then(function (resp) {
                    let day = resp.data
                    app.day.day = day.day

                    if(day.hotel != null) {
                        app.day.hotel.name = day.hotel.name
                        app.day.hotel.location = day.hotel.location
                        app.day.hotel.checkIn = day.hotel.checkIn
                        app.day.hotel.checkOut = day.hotel.checkOut
                    }

                    app.day.activitiesRaw = day.activities
                })
                .catch(function (resp) {
                    alert('Could not load day')
                })
            },
        }
    }
</script>
