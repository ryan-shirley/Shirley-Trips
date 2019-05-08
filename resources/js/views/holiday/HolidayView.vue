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
            :startPosition="$route.params.dayId"
            v-if="holiday.days"
            :key="'day_' + $route.params.dayId"
        ></date-slider>

        <router-view 
            :editMode="editMode"
            :key="'dayId_' + $route.params.dayId"
        ></router-view>

        <new-acitvity-picker :dayId="$route.params.dayId" v-if="editMode"></new-acitvity-picker>
        
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
                app.holiday = resp.data
                app.editPermission()
                
                
                if(app.$route.params.dayId != undefined) {
                    app.$router.push({ name: 'holiday.view.day', params: { 'dayId' : app.$route.params.dayId } })
                }
                else {
                    app.$router.push({ name: 'holiday.view.day', params: { 'dayId' : app.holiday.days[0].id } })
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
            editModeToggle () {
                this.editMode = !this.editMode
            }
        }
    }
</script>
