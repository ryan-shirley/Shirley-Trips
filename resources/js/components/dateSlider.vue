<template>
        <carousel
            :nav="false"
            :center="true"
            :dots="false"
            @changed="dayChanged"
            :startPosition='startIndex'
            :responsive="{
                0:{
                    items:6
                },
                600:{
                    items:10
                },
                1000:{
                    items:15
                },
                1500:{
                    items:20
                }
            }"
        >
        <div v-for="day in days" :key="day.id" class="text-center">
            <span class="letter">{{ getWeekDay(new Date(day.day)) }}</span>
            <span class="day">{{ day.day.slice(-2) }}</span>
        </div>
    </carousel>
</template>

<script>
    import carousel from 'vue-owl-carousel'

    export default {
        name: 'date-slider',
        components: { carousel },
        props: {
            parentData: Array,
            startPosition: {
                type: Number,
            }
        },
        data() {
            return {
                days: this.parentData,
                startIndex: 0
            }
        },
        beforeMount() {
            let app = this

            if(app.startPosition != 0) {
                let days = app.days

                for (var i = 0; i < days.length; i++) {
                    let day = days[i]

                    if(day.id == app.startPosition) {
                        app.startIndex = i
                        break
                    }
                }
            }
        },
        methods: {
            dayChanged(event) {
                let index = event.property.value
                this.$emit('childToParent', this.days[index].id)
            },
            getWeekDay(date){
                //Create an array containing each day, starting with Sunday.
                var weekdays = new Array(
                    "S", "M", "T", "W", "T", "F", "S"
                );
                //Use the getDay() method to get the day.
                var day = date.getDay();
                //Return the element that corresponds to that index.
                return weekdays[day];
            }
        }
    }
</script>
