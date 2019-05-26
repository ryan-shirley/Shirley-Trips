<template>
    <div class="container mb-5">

        <hotel-details 
            v-if="hotel && !reOrderMode"
            :hotel='hotel'
            :reOrderMode="reOrderMode"
            :dayId="dayId"
            :day="day"
            v-on:hotelDeleted="removeHotel"
            v-cloak
        />
        <section v-else-if="!reOrderMode" class="text-center">
            <p>It looks like you have no home for the day..</p>
        </section>
       <!--/.Hotel -->

        <section class="activity-list" v-if="sortedActivities && reOrderMode">

            <draggable 
                v-model="activities" 
                v-bind="dragOptions"
                @change="onReorderList"
            >
            <transition-group >
                <div v-for="activity in activities" :key="activity.activity_id">

                    <flight-details 
                        v-if="activity.airline_id != null"
                        :flight='activity'
                        :reOrderMode="reOrderMode"
                        :dayId="dayId"
                        v-on:flightDeleted="removeActivity"  
                    />

                    <comment-details 
                        v-if="activity.title != null"
                        :comment='activity'
                        :reOrderMode="reOrderMode"
                        :dayId="dayId"
                        v-on:commentDeleted="removeActivity"  
                    />

                    <video-card 
                        v-if="activity.url != null"
                        :video='activity'
                        :reOrderMode="reOrderMode"
                        v-on:videoDeleted="removeActivity"  
                    />

                </div>
            </transition-group>
            </draggable>
        </section>
        <section class="activity-list" v-else-if="sortedActivities && !reOrderMode">
            <div v-for="activity in sortedActivities" :key="activity.activity_id">

                <flight-details 
                    v-if="activity.airline_id != null"
                    :flight='activity'
                    :reOrderMode="reOrderMode"
                    :dayId="dayId"
                    v-on:flightDeleted="removeActivity"
                />

                <comment-details 
                    v-if="activity.title != null"
                    :comment='activity'
                    :reOrderMode="reOrderMode"
                    :dayId="dayId"
                    v-on:commentDeleted="removeActivity"  
                />

                <video-card 
                    v-if="activity.url != null"
                    :video='activity'
                    :reOrderMode="reOrderMode"
                    v-on:videoDeleted="removeActivity"  
                />

            </div>
        </section>
        <section v-else>
                <h3>You have no activities for this day..</h3>
        </section>
        <!--/.Activity-List -->

    </div> 
</template>

<script>
    import draggable from 'vuedraggable'

    export default {
        name: 'activity-list',
        props: {
            activitiesRaw: Array,
            hotel: Object,
            day: String,
            reOrderMode: Boolean,
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

                if(activitiesRaw != undefined || activitiesRaw != null) {
                    for (var i = 0; i < activitiesRaw.length; i++) {
                        let activity = activitiesRaw[i]

                        if(activity.comment_id != null) {
                            axios.get('/api/comment/' + activity.comment_id, {
                                    headers: { Authorization: "Bearer " + token }
                                })
                                .then(function (resp) {
                                    let data = resp.data
                                    data.activity_id = activity.id
                                    data.order = activity.order

                                    app.activities.push(data)
                                    app.checkAllActivitiesLoaded()
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
                                    data.order = activity.order

                                    app.activities.push(data)
                                    app.checkAllActivitiesLoaded()
                                })
                                .catch(function (resp) {
                                    console.log(resp)
                                    alert('Could not load flight')
                                })
                        }
                        else if (activity.video_id != null) {
                            axios.get('/api/videos/' + activity.video_id, {
                                    headers: { Authorization: "Bearer " + token }
                                })
                                .then(function (resp) {
                                    let data = resp.data
                                    data.activity_id = activity.id
                                    data.order = activity.order

                                    app.activities.push(data)
                                    app.checkAllActivitiesLoaded()
                                })
                                .catch(function (resp) {
                                    console.log(resp)
                                    alert('Could not load video')
                                })
                        }
                    }
                }
            },
            removeActivity(activity_id) {
                console.log('Removing Activity')
                let app = this

                let index = app.activities.findIndex(activity => {
                    return activity.activity_id == activity_id
                })
                app.activities.splice(index, 1)
            },
            removeHotel() {
                console.log('Removing Hotel')
                this.hotel = {}
            },
            onReorderList(event) {
                let app = this
                let token = localStorage.getItem('token')

                for (var i = 0; i < app.activities.length; i++) {
                    let activity = app.activities[i]
                    
                    axios.put('/api/activities/' + activity.activity_id, {
                        'order': (i + 1) 
                    }, {
                        headers: { Authorization: "Bearer " + token }
                    })
                    .then(function (resp) {
                        console.log('Updated')
                    })
                    .catch(function (resp) {
                        alert('Could not sort activity list')
                    })
                }
            },
            checkAllActivitiesLoaded() {
                if(this.activitiesRaw.length == this.activities.length) {
                    // Do someting
                }
            },
        },
        components: {
            draggable,
        },
        computed: {
            dragOptions() {
                return {
                    animation: 600,
                    group: "description",
                    disabled: false,
                    ghostClass: "ghost"
                };
            },
            sortedActivities: function () {
                return _.orderBy(this.activities, 'order')
            }
        }
    }
</script>
<style scoped>
    .ghost {
        opacity: 0.5;
    }
</style>
