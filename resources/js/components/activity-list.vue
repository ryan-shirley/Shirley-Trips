<template>
    <div class="container mb-5 mt-5">

        <h4>Day {{ day }}</h4>

        <p v-if="hotel.name">You are saying at the {{ hotel.name }} in {{ hotel.location }} today</p>

        <h3>Your activities are</h3>
        <div v-for="activity in activities" :key="activity.id">
            <div class="activity-card" v-if="activity.airline_id != null">
                <div class="body">
                    <h4>{{ activity.airline.name }}</h4>
                    {{ activity.flightNumber }}
                </div>
            </div>
            <!--/.Flight -->

            <div class="activity-card" v-if="activity.title != null">
                <div class="body">
                    <h4>{{ activity.title }}</h4>
                    {{ activity.subTitle }}
                </div>
                <div v-for="image in activity.images" :key="'activity_image' + image.id">
                        <img :src="image.path" />
                    </div>
            </div>
            <!--/.Comment -->
        </div>
    </div> 
</template>

<script>
    export default {
        name: 'activity-list',
        props: {
            activitiesRaw: Array,
            hotel: Object,
            day: String
        },
        data() {
            return {
                activities: []
            }
        },
        mounted() {
            console.log('Mounted')
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
                        console.log('Loading Comment')

                        axios.get('/api/comment/' + activity.comment_id, {
                                headers: { Authorization: "Bearer " + token }
                            })
                            .then(function (resp) {
                                let data = resp.data

                                app.activities.push(data)
                            })
                            .catch(function (resp) {
                                alert('Could not load comment')
                            })
                    }
                    else if (activity.flight_id != null) {
                        console.log('Loading Flight')

                        axios.get('/api/flight/' + activity.flight_id, {
                                headers: { Authorization: "Bearer " + token }
                            })
                            .then(function (resp) {
                                let data = resp.data

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
