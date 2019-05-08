<template>
    <div>
        <navigation 
            :editPermissions="editPer"
            v-on:editModeToggle="editModeToggle"
        ></navigation>

        <section v-if="holiday.image" class="bg-primary page-title bg-image" :style="{ 'background-image': 'url(' + holiday.image.path + ')' }">
            <div class="overlay">
                <h1>{{ holiday.title }}</h1>
                <p>{{ holiday.subTitle }}</p>
                <p>{{ holiday.beginDate.slice(-2) }} {{ month_name(new Date(holiday.beginDate)) }} - {{ holiday.endDate.slice(-2) }} {{ month_name(new Date(holiday.endDate)) }}</p>
            </div>
        </section>

        <date-slider 
            :parentData="holiday.days" 
            :startPosition="day.id"
            v-on:childToParent="loadDay"  
            v-if="holiday.days"
            :key="'day_' + day.id"
        ></date-slider>

        <div class="row justify-content-md-center">
            <div class="col-12 col-md-4">
                <activity-list 
                    :activitiesRaw="day.activitiesRaw" 
                    :hotel="day.hotel"
                    :day="day.day"
                    :dayId="day.id"
                    :editMode="editMode"
                    v-if="day.activitiesRaw.length || day.day.length"
                    :key="day.day"
                />
            </div>
        </div>

        <new-acitvity-picker :dayId="day.id" :day="day.day" v-if="editMode"></new-acitvity-picker>
        
    </div>
</template>

<script>

    export default {
        mounted() {
            let app = this
            let id = app.$route.params.id
            let dayStartPosition = app.$route.params.dayStartPosition
            let token = localStorage.getItem('token')

            axios.get('/api/holiday/' + id, {
                headers: { Authorization: "Bearer " + token }
            })
            .then(function (resp) {
                app.holiday = resp.data
                app.editPermission()

                if(dayStartPosition != undefined) {
                    app.loadDay(dayStartPosition)
                }
                else {
                    app.loadDay(app.holiday.days[0].id)
                }
            })
            .catch(function (resp) {
                alert('Could not load holiday')
            })
        },
        data() {
            return {
                holiday: {},
                editPer: false,
                editMode: false,
                day: {
                    id: 0,
                    day: '',
                    hotel: {
                        name: '',
                        location: '',
                        checkIn: '',
                        checkOut: '',
                    },
                    activitiesRaw: []
                },
            }
        },
        methods: {
            editPermission() {
                let app = this
                let users = app.holiday.users

                for (var i = 0; i < users.length ; i++) {
                    if(users[i].pivot.editPermission == true) {
                        app.editPer = true;

                        if(app.$route.params.editMode == true) {
                            app.editMode = true
                        }
                        return;
                    }
                }
            },
            month_name(dt){
                let mlist = [ "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December" ];
                return mlist[dt.getMonth()];
            },
            loadDay (day_id) {
                let app = this
                let token = localStorage.getItem('token')
                app.day.hotel = {}
                app.day.activitiesRaw = []

                axios.get('/api/day/' + day_id, {
                    headers: { Authorization: "Bearer " + token }
                })
                .then(function (resp) {
                    let day = resp.data
                    app.day.id = day.id
                    app.day.day = day.day

                    if(day.hotel != null) {
                        app.day.hotel = day.hotel
                    }

                    app.day.activitiesRaw = day.activities
                })
                .catch(function (resp) {
                    alert('Could not load day')
                })
            },
            editModeToggle () {
                this.editMode = !this.editMode
            }
        }
    }
</script>
