<template>
        <carousel
            :nav="false"
            :center="true"
            :dots="false"
            :items="18"
            @changed="dayChanged"
        >
        <div v-for="day in days" :key="day.id" class="text-center">
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
            parentData: Array
        },
        data() {
            return {
                days: this.parentData
            }
        },
        methods: {
            dayChanged(event) {
                let index = event.property.value
                console.log('Day changed. Index in array ' + index)
                this.$emit('childToParent', this.days[index].id)
            }
        }
    }
</script>
