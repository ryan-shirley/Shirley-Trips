<template>
    <div>
        <navigation 
            :editPermissions="editPer"
            :owner="owner"
            v-on:reOrderModeToggle="reOrderModeToggle"
        ></navigation>

        <section v-if="holiday.image" class="bg-primary page-title bg-image" :style="{ 'background-image': 'url(' + holiday.image.path + ')' }" v-once>
            <div class="overlay">
                <h1>{{ holiday.title }}</h1>
                <p>{{ holiday.subTitle }}</p>
                <p>{{ holiday.beginDate.slice(-2) }} {{ month_name(new Date(holiday.beginDate)) }} - {{ holiday.endDate.slice(-2) }} {{ month_name(new Date(holiday.endDate)) }}</p>
            </div>
        </section>

        <date-slider 
            :parentData="holiday.days" 
            :startPosition="$route.params.dayId"
            v-if="holiday.days"
            :key="'day_' + $route.params.dayId"
        ></date-slider>

        <router-view 
            :reOrderMode="reOrderMode"
            :key="'dayId_' + $route.params.dayId"
        ></router-view>

        <new-acitvity-picker :dayId="$route.params.dayId" v-if="editPer" v-once></new-acitvity-picker>

        <footer-global />
        
    </div>
</template>

<script>

    export default {
        mounted() {
            let app = this
            let id = app.$route.params.holidayId
            let token = localStorage.getItem('token')

            axios.get('/api/holiday/' + id, {
                    headers: { Authorization: "Bearer " + token }
                })
                .then(function (resp) {
                    app.holiday = resp.data.holiday
                    app.user = resp.data.user
                    app.editPermission()
                    app.checkIsOwner()
                    
                    if(app.$route.params.dayId != undefined) {
                        // Day was selected
                        app.$router.replace({ name: 'holiday.view.day', params: { 'dayId' : app.$route.params.dayId } })
                    }
                    else {
                        // No day specified
                        let currentDate = new Date();

                        for (var i = 0; i < app.holiday.days.length ; i++) {
                            let holidayDate = new Date(app.holiday.days[i]['day'])

                            if(currentDate.getDate() + ' ' + currentDate.getMonth() + ' ' + currentDate.getYear() === holidayDate.getDate() + ' ' + holidayDate.getMonth() + ' ' + holidayDate.getYear()) {
                                console.log('Current date is during holiday')
                                app.$router.replace({ name: 'holiday.view.day', params: { 'dayId' : app.holiday.days[i].id } })
                                return;
                            }
                        }

                        app.$router.replace({ name: 'holiday.view.day', params: { 'dayId' : app.holiday.days[0].id } })
                    }
                })
                .catch(function (resp) {
                    alert('Could not load holiday')
                })

        },
        data() {
            return {
                user: {},
                holiday: {},
                editPer: false,
                reOrderMode: false,
                owner: false
            }
        },
        methods: {
            editPermission() {
                let app = this
                let users = app.holiday.users

                for (var i = 0; i < users.length ; i++) {
                    if(users[i].pivot.editPermission == true && users[i].id == app.user.id) {
                        app.editPer = true;
                        return;
                    }
                }
            },
            checkIsOwner() {
                let app = this
                let users = app.holiday.users

                for (var i = 0; i < users.length ; i++) {
                    if(users[i].pivot.owner == true && users[i].id == app.user.id) {
                        app.owner = true;
                        return;
                    }
                }
            },
            month_name(dt){
                let mlist = [ "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December" ];
                return mlist[dt.getMonth()];
            },
            reOrderModeToggle () {
                this.reOrderMode = !this.reOrderMode
            }
        }
    }
</script>
