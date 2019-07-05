<template>
    <router-link class="holiday" :to="{ name:'holiday.view', params: { 'holidayId' :holiday.id }}">
        <div class="card" :style="{ 'background-image': 'url(' + holiday.image.path + ')' }">
            <div class="overlay">
                <h2 class="title">{{ holiday.title }}</h2>
                <p class="subTitle">{{ holiday.subTitle }}</p>
                <p class="date">{{ holiday.beginDate.slice(-2) }} {{ month_name(new Date(holiday.beginDate)) }} - {{ holiday.endDate.slice(-2) }} {{ month_name(new Date(holiday.endDate)) }}</p>
            </div>
        </div>
    </router-link>
</template>

<script>
    export default {
        name: 'holiday-card',
        props: {
            holiday: Object,
        },
        methods: {
            month_name(dt){
                let mlist = [ "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December" ];
                return mlist[dt.getMonth()];
            }
        }
    }
</script>
