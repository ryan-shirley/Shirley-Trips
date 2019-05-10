<template>
    <div>
        <router-link :to="{ name:'admin.airlines.add'}" class="btn btn-primary">Add</router-link>

        <br /><br />

        <div class="activity-card" v-for="airline in airlines" :key="airline.id">
            <div class="body">
                <h4>{{ airline.name }}</h4>
            </div>
            <img :src="airline.image.path">
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            let app = this
            let token = localStorage.getItem('token')

            axios.get('/api/airlines', {
                headers: { Authorization: "Bearer " + token }
            })
            .then(function (resp) {
                app.airlines = resp.data;
            })
            .catch(function (resp) {
                alert('Could not load airlines')
            })
        },
        data() {
            return {
                airlines: []
            }
        },
        methods: {
        }
    }
</script>
