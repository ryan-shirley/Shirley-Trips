<template>
    <router-link 
        :to="{ name: 'holiday.hotel.view', params: { 'hotelId' :hotel.id } }"
        class="card-wrapper" 
        v-if="hotel.checkIn == day" 
        v-cloak >
        <div class="card hotel">
            <img v-if="hotelImagePath" :src="hotelImagePath" class="card-img-top" :alt="hotel.name">
            <div class="card-body">
                <h5 class="card-title">{{ hotel.name }}</h5>
                <p class="card-text">{{ hotel.location }}</p>
            </div>
        </div>
    </router-link>
    <div v-else class="text-center">
        <p>You are staying at the <router-link 
        :to="{ name: 'holiday.hotel.view', params: { 'hotelId' :hotel.id } }"
        class="card-wrapper">{{ hotel.name }} in {{ hotel.location }}</router-link> today.</p>
    </div>
    <!--/.Hotel -->
</template>

<script>
    export default {
        name: 'hotel-details',
        props: {
            hotel: Object,
            editMode: Boolean,
            dayId: Number,
            day: String
        },
        data() {
            return {
                hotelImagePath: ''
            }
        },
        mounted() {
            let app = this
            let token = localStorage.getItem('token')
            
            axios.get('/api/images/' + app.hotel.image_id, {
                headers: { Authorization: "Bearer " + token }
            })
            .then(resp => {
                app.hotelImagePath = resp.data.path
            })
            .catch(error => alert("Could not get image for hotel"))
        },
    }
</script>
