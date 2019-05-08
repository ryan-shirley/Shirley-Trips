<template>
    <div class="card flight">
        <div class="card-header bg-primary text-white">
            <div class="row align-items-center">
                <div class="col-2">
                    <span class="date">
                        <p class="number">{{ flight.originDayTime.substr(8, 2) }}</p>
                        <p class="month">{{ month_name(new Date(flight.originDayTime)) }}</p>
                    </span>
                </div>
                <div class="col-8 airline-name">
                    {{ flight.airline.name }}
                </div>
                <div class="col-2 airline-logo">
                    <img :src="airlineImagePath" :alt="flight.airline.name">
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="row details">
                <div class="col-4">
                    <p class="ar-long">{{ flight.originAirportLong }}</p>
                    <p class="ar-short">{{ flight.originAirportShort }}</p>
                    <p>{{ tConvert(flight.originDayTime.slice(11, -3)) }}</p>
                </div>
                <div class="col-4">
                    <p>{{ flight.duration }}</p>
                    <p>{{ flight.flightNumber }}</p>
                </div>
                <div class="col-4">
                    <p class="ar-long">{{ flight.destinationAirportLong }}</p>
                    <p class="ar-short">{{ flight.destinationAirportShort }}</p>
                    <p>{{ tConvert(flight.destinationDayTime.slice(11, -3)) }}</p>
                </div>
            </div>

            <div v-if="editMode">
                <router-link :to="{ name:'holiday.edit.flight', params: { 'day' : dayId, 'flightId' : flight.id } }" class="btn btn-secondary">Edit</router-link>
                <button type="button" class="btn btn-danger" @click="deleteFlight(flight.id)">Delete</button>
            </div>
        </div>
    </div>
    <!--/.Flight -->
</template>

<script>
    export default {
        name: 'flight-details',
        props: {
            flight: Object,
            editMode: Boolean,
            dayId: Number
        },
        data() {
            return {
                airlineImagePath: ''
            }
        },
        mounted() {
            let app = this
            let token = localStorage.getItem('token')
            
            axios.get('/api/images/' + app.flight.airline.image_id, {
                headers: { Authorization: "Bearer " + token }
            })
            .then(resp => {
                app.airlineImagePath = resp.data.path
            })
            .catch(error => alert("Could not get image for airline"))
        },
        methods: {
            deleteFlight(id) {
                if(confirm("Are you sure you want to delete this flight?")) {
                    let app = this
                    let token = localStorage.getItem('token')
                    
                    axios.delete('/api/flight/' + app.flight.id, {
                        headers: { Authorization: "Bearer " + token },
                        data: { 'activity_id': app.flight.activity_id }
                    })
                    .then(resp => {
                       app.$emit('flightDeleted', app.flight.activity_id)
                    })
                    .catch(error => alert("Could not delete flight"))
                }
            },
            month_name(dt){
                let mlist = [ "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December" ];
                return mlist[dt.getMonth()];
            },
            tConvert (time) {
                // Check correct time format and split into components
                time = time.toString ().match (/^([01]\d|2[0-3])(:)([0-5]\d)?$/) || [time];

                if (time.length > 1) { // If time format correct
                    time = time.slice (1);  // Remove full string match value
                    time[5] = +time[0] < 12 ? ' am' : ' pm'; // Set AM/PM
                    time[0] = +time[0] % 12 || 12; // Adjust hours
                }
                return time.join (''); // return adjusted time or original string
            }
        }
    }
</script>
