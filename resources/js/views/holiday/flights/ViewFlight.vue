<template>
    <div class="comment">
        <navigation
            :viewing='true'
            v-on:edit="switchToEditFlight"
            v-on:delete="deleteFlight"
        />

        <section class="bg-primary page-title">
                <h1>{{ flight.flightNumber }}</h1>
                <p>{{ flight.originAirportShort }} - {{ flight.destinationAirportShort }}</p>
        </section>

    </div>
</template>

<script>
    export default {
        data() {
            return {
                flight: {},
                activity_id: ''
            }
        },
        mounted() {
            let app = this
            let token = localStorage.getItem('token')
            let flightId = app.$route.params.flightId

            // Load individual flight
            axios.get('/api/flight/' + flightId, {
                headers: { Authorization: "Bearer " + token }
            })
            .then(resp => {
                app.flight = resp.data
                app.activity_id = resp.data.activity.id
            })
            .catch(errors => alert('Could not load flight'))
        },
        methods: {
            switchToEditFlight() {
                this.$router.push({name: 'holiday.edit.flight', params: { 'dayId': this.$route.params.dayId, 'flightId': this.flight.id } })
            },
            deleteFlight() {
                if(confirm("Are you sure you want to delete this flight?")) {
                    let app = this
                    let token = localStorage.getItem('token')
                    
                    axios.delete('/api/flight/' + app.flight.id, {
                        headers: { Authorization: "Bearer " + token },
                        data: { 'activity_id': app.activity_id }
                    })
                    .then(resp => {
                        app.$router.push({name: 'holiday.view.day', params: { 'dayId': app.$route.params.dayId } })
                    })
                    .catch(error => alert("Could not delete flight"))
                }
            }
        }
    }
</script>
