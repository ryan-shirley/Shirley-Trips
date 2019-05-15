<template>
    <div class="row justify-content-md-center">
        <div class="col-12 col-md-4">
            <activity-list 
                :activitiesRaw="day.activities" 
                :hotel="day.hotel"
                :day="day.day"
                :dayId="day.id"
                :reOrderMode="reOrderMode"
                :key="day.day"
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
                day: {}
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
                app.day = resp.data

                app.day.activities.sort((a, b) => (a.order > b.order) ? 1 : -1)
            })
            .catch(function (resp) {
                alert('Could not load day')
            })
        },
        methods: {
        }
    }
</script>
