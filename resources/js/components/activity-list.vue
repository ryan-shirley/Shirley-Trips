<template>
    <div class="container mb-5 mt-5">

        <h4>Day {{ day }}</h4>

        <section v-if="hotel.name">
             <p>You are saying at the {{ hotel.name }} in {{ hotel.location }} today</p>
        </section>
        <section v-else>
            <p>It looks like you have no home for the day..</p>
        </section>
       <!--/.Hotel -->

        <section class="activity-list" v-if="activities.length > 0">

            <h3>Your activities are</h3>
            <div v-for="activity in activities" :key="activity.activity_id">
                <div class="activity-card" v-if="activity.airline_id != null">
                    <div class="body">
                        <h4>{{ activity.airline.name }}</h4>
                        {{ activity.flightNumber }}
                        <router-link :to="{ name:'holiday.edit.flight', params: { 'day' : dayId, 'flightId' : activity.id } }" v-if="editMode" class="btn btn-secondary">Edit</router-link>
                    </div>
                </div>
                <!--/.Flight -->

                <div class="activity-card" v-if="activity.title != null">
                    <div class="body">
                        <h4>{{ activity.title }}</h4>
                        {{ activity.subTitle }}
                        <router-link :to="{ name:'holiday.edit.message', params: { 'day' : dayId, 'commentId' : activity.id } }" v-if="editMode" class="btn btn-secondary">Edit</router-link>
                    </div>
                    <div v-for="image in activity.images" :key="'activity_image' + image.id">
                        <img :src="image.path" />
                    </div>
                </div>
                <!--/.Comment -->
            </div>
        </section>
        <section v-else>
                <h3>You have no activities for this day..</h3>
        </section>
        <!--/.Activity-List -->

    </div> 
</template>

<script>
    export default {
        name: 'activity-list',
        props: {
            activitiesRaw: Array,
            hotel: Object,
            day: String,
            editMode: Boolean,
            dayId: Number
        },
        data() {
            return {
                activities: []
            }
        },
        mounted() {
            this.loadActivities()
        },
        methods: {
            loadActivities() {
                let app = this
                let token = localStorage.getItem('token')
                let activitiesRaw = app.activitiesRaw

                for (var i = 0; i < activitiesRaw.length; i++) {
                    let activity = activitiesRaw[i]

                    if(activity.comment_id != null) {
                        axios.get('/api/comment/' + activity.comment_id, {
                                headers: { Authorization: "Bearer " + token }
                            })
                            .then(function (resp) {
                                let data = resp.data
                                data.activity_id = activity.id

                                app.activities.push(data)
                            })
                            .catch(function (resp) {
                                alert('Could not load comment')
                            })
                    }
                    else if (activity.flight_id != null) {
                        axios.get('/api/flight/' + activity.flight_id, {
                                headers: { Authorization: "Bearer " + token }
                            })
                            .then(function (resp) {
                                let data = resp.data
                                data.activity_id = activity.id

                                app.activities.push(data)
                            })
                            .catch(function (resp) {
                                alert('Could not load flight')
                            })
                    }
                }
            }
        }
    }
</script>
