<template>
    <div class="comment">
        <navigation
            :viewing='true'
            :backArrow='true'
            v-on:edit="switchToEditFlight"
            v-on:delete="deleteFlight"
        />

        <section class="bg-primary page-title" v-if="flight">
                <h1>{{ flight.originAirportLong }} - {{ flight.destinationAirportLong }}</h1>
                <p>No. {{ flight.flightNumber }}</p>
                <p v-if="liveFlightInfo && liveFlightInfo.status">{{ liveFlightInfo.status }}</p>
                <p v-else>{{ liveFlightInfo }}</p>
        </section>

        <section class="container" v-if="flight">
            <div class="card">
                <div class="card-body">
                    <p>Duration - {{ secondsToHm(flight.duration) }}</p>
                    <p>Origin - {{ flight.originDayTime }}</p>
                    <p>Destination - {{ flight.destinationDayTime }}</p>

                    <div v-if="liveFlightInfo && liveFlightInfo.progress_percent > 0">
                        <h3 class="mb-3">Flight Progress</h3>
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" :style="'width: ' + liveFlightInfo.progress_percent + '%;'" :aria-valuenow="liveFlightInfo.progress_percent" aria-valuemin="0" aria-valuemax="100">{{ liveFlightInfo.progress_percent }}%</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <footer-global />

    </div>
</template>

<script>
    export default {
        data() {
            return {
                flight: {},
                activity_id: '',
                liveFlightInfo: null
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

                // Load Live flight info
                axios.get('/api/realtimeflight/' + flightId, {
                    headers: { Authorization: "Bearer " + token }
                })
                .then(resp => {
                    app.liveFlightInfo = resp.data
                })
                .catch(errors => {
                    console.log(errors)
                })
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
            },
            secondsToHm(d) {
                d = Number(d);
                var h = Math.floor(d / 3600);
                var m = Math.floor(d % 3600 / 60);
                var s = Math.floor(d % 3600 % 60);

                var hDisplay = h > 0 ? h + (h == 1 ? " hour " : " hours ") : "";
                var mDisplay = m > 0 ? m + (m == 1 ? " minute " : " minutes") : "";
                return hDisplay + mDisplay; 
            },
        },
    }
</script>
