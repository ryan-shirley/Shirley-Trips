<template>
    <div class="comment">
        <navigation
            :viewing='true'
            v-on:edit="switchToEditFlight"
            v-on:delete="deleteFlight"
        />

        <section class="bg-primary page-title" v-if="flight">
                <h1>{{ flight.originAirportLong }} - {{ flight.destinationAirportLong }}</h1>
                <p>No. {{ flight.flightNumber }}</p>
                <p v-if="flightInfo">{{ getFlightStatus }}</p>
        </section>

        <section class="container" v-if="flight">
            <div class="card">
                <div class="card-body">
                    <p>Duration - {{ secondsToHm(flight.duration) }}</p>
                    <!-- <p>Origin - {{ flight.originDayTime }}</p>
                    <p>Destination - {{ flight.destinationDayTime }}</p> -->

                    <div v-if="progress > 0">
                        <h3 class="mb-3">Flight Progress</h3>
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" :style="'width: ' + progress + '%;'" :aria-valuenow="progress" aria-valuemin="0" aria-valuemax="100">{{ progress }}%</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>
</template>

<script>
    export default {
        data() {
            return {
                flight: {},
                activity_id: '',
                flightInfo: null,
                progress: -1
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
                axios.get('/api/realtimeflight/' + app.flight.flightNumber, {
                    headers: { Authorization: "Bearer " + token }
                })
                .then(resp => {
                    app.flightInfo = resp.data
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
        computed: {
            getFlightStatus() {
                let app = this

                // Check Flights Exist
                if (typeof app.flightInfo.FlightInfoStatusResult.flights !== 'undefined') {
                    let flights = app.flightInfo.FlightInfoStatusResult.flights
                    let pattern = /(\d{2})\/(\d{2})\/(\d{4})/

                    // Loop through live flight info to get flight for same day
                    for (var i = 0; i < flights.length ; i++) {

                        let date1 = new Date(flights[i].filed_departure_time.date.replace(pattern,'$3-$2-$1'))
                        date1.setHours(0,0,0,0)
                        let date2 = new Date(app.flight.originDayTime)
                        date2.setHours(0,0,0,0)
                        
                        if (date1.getTime() === date2.getTime()) {
                            app.progress = flights[i].progress_percent

                            return 'Status - ' + flights[i].status
                        }

                    }
                }

                return 'Flight Status not available';
            }
        }
    }
</script>
