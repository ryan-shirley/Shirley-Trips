<template>
    <div>
        <navigation
            :viewing='true'
            v-on:edit="switchToEditHotel"
            v-on:delete="deleteHotel"
        />

        <section v-if="hotel.image" class="bg-primary page-title bg-image mb-5" :style="{ 'background-image': 'url(' + hotel.image.path + ')' }">
            <div class="overlay">
                <h1>{{ hotel.name }}</h1>
                <p>{{ hotel.location }}</p>
            </div>
        </section>

        <section class="container" v-if="hotel.checkIn">
            <div class="row justify-content-md-center">
                <div class="col-12 col-md-8">
                    <div class="row">
                        <div class="col-6">
                            <div class="card text-white bg-primary mb-3">
                                <div class="card-header text-uppercase text-center">Check-In</div>
                                <div class="card-body text-center">
                                    <h5 class="card-title">{{ getWeekDay(new Date(hotel.checkIn)) }} {{ hotel.checkIn.slice(-2) }}</h5>
                                    <p class="card-text">{{ months[new Date(hotel.checkIn).getMonth()] }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card text-white bg-primary mb-3">
                                <div class="card-header text-uppercase text-center">Check-Out</div>
                                <div class="card-body text-center">
                                    <h5 class="card-title">{{ getWeekDay(new Date(hotel.checkOut)) }} {{ hotel.checkOut.slice(-2) }}</h5>
                                    <p class="card-text">{{ months[new Date(hotel.checkOut).getMonth()] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card image-only mt-3">
                        <img :src="hotel.image.path" class="img-fluid" :alt="hotel.name">
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
    export default {
        mounted() {
            let app = this
            let token = localStorage.getItem('token')
            let hotelId = app.$route.params.hotelId

            // Load individual hotel
            axios.get('/api/hotels/' + hotelId, {
                headers: { Authorization: "Bearer " + token }
            })
            .then(resp => {
                app.hotel = resp.data
            })
            .catch(errors => alert('Could not load hotel'))
        },
        data() {
            return {
                hotel: {},
                months: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oc','Nov','Dec ']
            }
        },
        methods: {
            getWeekDay(date){
                //Create an array containing each day, starting with Sunday.
                var weekdays = new Array(
                    "Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"
                );
                //Use the getDay() method to get the day.
                var day = date.getDay();
                //Return the element that corresponds to that index.
                return weekdays[day];
            },
            switchToEditHotel() {
                this.$router.push({name: 'holiday.edit.hotel', params: { 'dayId': this.$route.params.dayId, 'hotelId': this.hotel.id } })
            },
            deleteHotel() {
                if(confirm("Are you sure you want to delete this hotel?")) {
                    let app = this
                    let token = localStorage.getItem('token')
                    
                    axios.delete('/api/hotels/' + app.hotel.id, {
                        headers: { Authorization: "Bearer " + token }
                    })
                    .then(resp => {
                       app.$router.push({name: 'holiday.view.day', params: { 'dayId': app.$route.params.dayId } })
                    })
                    .catch(error => alert("Could not delete hotel"))
                }
            }
        }
    }
</script>