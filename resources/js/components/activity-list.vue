<template>
    <div class="container mb-5">

        <hotel-details 
            v-if="hotel"
            :hotel='hotel'
            :editMode="editMode"
            :dayId="dayId"
            :day="day"
        />
        <section v-else class="text-center">
            <p>It looks like you have no home for the day..</p>
        </section>
       <!--/.Hotel -->

        <section class="activity-list" v-if="activities">

            <draggable 
                v-model="activities" 
                v-bind="dragOptions"
                v-if="editMode"
                @change="onReorderList"
            >
            <transition-group >
                <div v-for="activity in activities" :key="activity.activity_id">

                    <flight-details 
                        v-if="activity.airline_id != null"
                        :flight='activity'
                        :editMode="editMode"
                        :dayId="dayId"
                        v-on:flightDeleted="removeActivity"  
                    />

                    <comment-details 
                        v-if="activity.title != null"
                        :comment='activity'
                        :editMode="editMode"
                        :dayId="dayId"
                        v-on:commentDeleted="removeActivity"  
                    />

                </div>
            </transition-group>
            </draggable>
            <div v-for="activity in activities" :key="activity.activity_id" v-else>

                    <flight-details 
                        v-if="activity.airline_id != null"
                        :flight='activity'
                        :editMode="editMode"
                        :dayId="dayId"
                        v-on:flightDeleted="removeActivity"  
                    />

                    <comment-details 
                        v-if="activity.title != null"
                        :comment='activity'
                        :editMode="editMode"
                        :dayId="dayId"
                        v-on:commentDeleted="removeActivity"  
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
            },
            removeActivity(activity_id) {
                console.log('Removing Activity')
                let app = this

                let index = app.activities.findIndex(activity => {
                    return activity.activity_id == activity_id
                })
                app.activities.splice(index, 1)
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
            }
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
            }
        }
    }
</script>
<style scoped>
    .ghost {
        opacity: 0.5;
    }
</style>
