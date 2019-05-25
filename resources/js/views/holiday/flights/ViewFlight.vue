<template>
    <div class="comment">
        <navigation
            :viewing='true'
            v-on:edit="switchToEditFlight"
            v-on:delete="deleteFlight"
        />

        <section class="bg-primary page-title" v-if="flightInfo">
                <h1>{{ getFlightInfo.origin.city }} - {{ getFlightInfo.destination.city }}</h1>
                <p>No. {{ flight.flightNumber }}</p>
                <p>Status - {{ getFlightInfo.status }}</p>
        </section>

        <section class="container" v-if="getFlightInfo">
            <div class="card">
                <div class="card-body">
                    <p>Duration - {{ secondsToHm(getFlightInfo.filed_ete) }}</p>
                    <p>Estimated Depart date - {{ getFlightInfo.estimated_departure_time.date }}, time - {{ getFlightInfo.estimated_departure_time.time }} {{ getFlightInfo.estimated_departure_time.tz }}</p>
                    <p>Estimated Arrive date - {{ getFlightInfo.estimated_arrival_time.date }}, time - {{ getFlightInfo.estimated_arrival_time.time }} {{ getFlightInfo.estimated_arrival_time.tz }}</p>

                    <div v-if="getFlightInfo.actual_arrival_time.date && getFlightInfo.actual_departure_time.date">
                        <p>Actual Depart date - {{ getFlightInfo.actual_arrival_time.date }}, time - {{ getFlightInfo.actual_arrival_time.time }} {{ getFlightInfo.actual_arrival_time.tz }}</p>
                        <p>Actual Arrive date - {{ getFlightInfo.actual_departure_time.date }}, time - {{ getFlightInfo.actual_departure_time.time }} {{ getFlightInfo.actual_departure_time.tz }}</p>
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
                flightInfo: {}
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
                .catch(errors => alert('Could not load flight information'))
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
            ConvertTimeformat(format, str) {
                var time = str.slice(0, -2) + ' ' +  str.slice(-2)
                var hours = Number(time.match(/^(\d+)/)[1]);
                var minutes = Number(time.match(/:(\d+)/)[1]);
                var AMPM = time.match(/\s(.*)$/)[1];
                if (AMPM == "PM" && hours < 12) hours = hours + 12;
                if (AMPM == "AM" && hours == 12) hours = hours - 12;
                var sHours = hours.toString();
                var sMinutes = minutes.toString();
                if (hours < 10) sHours = "0" + sHours;
                if (minutes < 10) sMinutes = "0" + sMinutes;
                
                return sHours + ":" + sMinutes;
            }
        },
        computed: {
            getFlightInfo() {
                let app = this
                let flights = app.flightInfo.FlightInfoStatusResult.flights
                let pattern = /(\d{2})\/(\d{2})\/(\d{4})/

                // Loop through live flight info to get flight for same day
                for (var i = 0; i < flights.length ; i++) {
                    if(new Date(flights[i].filed_departure_time.date.replace(pattern,'$3-$2-$1')).getDay() === new Date(app.flight.originDayTime).getDay()) {
                        return flights[i]
                    }
                }

                return false;
            }
        }
    }
</script>
