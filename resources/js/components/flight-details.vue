<template>
    <router-link :to="{ name:'holiday.view.flight', params: { 'flightId' :flight.id }}" class="card-wrapper" v-cloak>
        <div class="card flight">
            <div class="card-header bg-primary text-white">
                <div class="row align-items-center">
                    <div class="col-2">
                        <span class="date">
                            <p class="number">{{ flight.originDayTime.substr(8, 2) }}</p>
                            <p class="month">{{ month_name(new Date(flight.originDayTime.substring(0,10))) }}</p>
                        </span>
                    </div>
                    <div class="col-8 airline-name">
                        {{ flight.airline.name }}
                    </div>
                    <div class="col-2 airline-logo">
                        <img :src="flight.airline.image.path" :alt="flight.airline.name">
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
                        <i class="fas fa-plane"></i>
                        <p class="duration">{{ secondsToHm(flight.duration) }}</p>
                        <p v-if="flight.layoverLength">Layover - {{ secondsToHm(flight.layoverLength * 60) }}</p>
                    </div>
                    <div class="col-4">
                        <p class="ar-long">{{ flight.destinationAirportLong }}</p>
                        <p class="ar-short">{{ flight.destinationAirportShort }}</p>
                        <p>{{ tConvert(flight.destinationDayTime.slice(11, -3)) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </router-link>
    <!--/.Flight -->
</template>

<script>
    export default {
        name: 'flight-details',
        props: {
            flight: Object,
            reOrderMode: Boolean,
            dayId: Number
        },
        methods: {
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
            },
            secondsToHm(d) {
                d = Number(d);
                var h = Math.floor(d / 3600);
                var m = Math.floor(d % 3600 / 60);
                var s = Math.floor(d % 3600 % 60);

                var hDisplay = h > 0 ? h + (h == 1 ? " hr " : " hrs ") : "";
                var mDisplay = m > 0 ? m + (m == 1 ? " min " : " mins") : "";
                return hDisplay + mDisplay; 
            },
        }
    }
</script>
