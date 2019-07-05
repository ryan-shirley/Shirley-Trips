<template>
    <router-link 
        :to="{ name: 'holiday.hotel.view', params: { 'hotelId' :hotel.id } }"
        class="card-wrapper" 
        v-if="hotel.checkIn == day" 
        v-cloak >
        <div class="card hotel">
            <img :src="hotel.image.path" class="card-img-top" :alt="hotel.name">
            <div class="card-body">
                <h5 class="card-title">{{ hotel.name }}</h5>
                <p class="card-text">{{ hotel.location }}</p>
            </div>
        </div>
    </router-link>
    <router-link 
        :to="{ name: 'holiday.hotel.view', params: { 'hotelId' :hotel.id } }"
        class="card-wrapper" v-else>
        <div class="activity-card">
            <div class="body">
                <h4>{{ hotel.name }}</h4>
                <p>{{ hotel.location }}</p>
            </div>
            <img :src="hotel.image.path" :alt="hotel.name">
        </div>
    </router-link>
    <!--/.Hotel -->
</template>

<script>
    export default {
        name: 'hotel-details',
        props: {
            hotel: Object,
            editMode: Boolean,
            day: String
        }
    }
</script>
