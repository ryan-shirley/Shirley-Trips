<template>
    <div class="container mb-5">

        <hotel-details 
            v-if="hotel && !reOrderMode"
            :hotel='hotel'
            :reOrderMode="reOrderMode"
            :day="day"
            v-on:hotelDeleted="removeHotel"
            v-cloak
        />
        <section v-else-if="!reOrderMode" class="text-center">
            <p>It looks like you have no home for the day..</p>
        </section>
       <!--/.Hotel -->

        <section class="activity-list" v-if="reOrderMode && activities">

            <draggable 
                v-model="activitiesList" 
                v-bind="dragOptions"
                @change="onReorderList"
            >
            <transition-group >
                <div v-for="activity in activitiesList" :key="activity.activityId">

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
        <section class="activity-list" v-else-if="!reOrderMode && activities">
            <div v-for="activity in activitiesList" :key="activity.activityId">

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
            activities: Array,
            hotel: Object,
            day: String,
            reOrderMode: Boolean,
            dayId: Number
        },
        data() {
            return {
                activitiesList: []
            }
        },
        mounted() {
            this.activitiesList = this.activities
        },
        methods: {
            removeActivity(activityId) {
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

                for (var i = 0; i < app.activitiesList.length; i++) {
                    let activity = app.activitiesList[i]

                    let order = i + 1
                    
                    // Update on server
                    axios.put('/api/activities/' + activity.activityId, {
                        'order': order
                    }, {
                        headers: { Authorization: "Bearer " + token }
                    })
                    .then(function (resp) {
                        // console.log('Updated')
                        // Update local list
                        app.activitiesList[order - 1].order = order
                    })
                    .catch(function (resp) {
                        // console.log(resp)
                        alert('Could not sort activity list')
                    })
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
        }
    }
</script>
<style scoped>
    .ghost {
        opacity: 0.5;
    }
</style>
