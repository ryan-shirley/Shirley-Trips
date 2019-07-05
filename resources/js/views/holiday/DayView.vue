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
                hotel: {}
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
            })
            .catch(function (resp) {
                alert('Could not load day')
            })
        }
    }
</script>
