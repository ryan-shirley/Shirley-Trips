<template>
    <div>
        <h2 class="text-center">TinyPNG usage this month</h2>
        <h4 class="text-center" v-if="compressionCount != null">
            <span class="badge badge-pill"
                v-bind:class="{ 'badge-success': compressionCount < 400, 'badge-warning': compressionCount >= 400 && compressionCount < 500, 'badge-danger': compressionCount >= 500 }" >
                {{ compressionCount }} / 500
            </span>
        </h4>

        <div class="progress mt-5" v-if="compressionCount != null && compressionCount > 0">
            <div class="progress-bar progress-bar-striped" role="progressbar" :style="'width: ' + Math.floor(compressionCount/500*100) + '%;'" :aria-valuenow="Math.floor(compressionCount/500*100)" aria-valuemin="0" aria-valuemax="100">{{ Math.floor(compressionCount/500*100) }}%</div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            let app = this
            let token = localStorage.getItem('token')

            axios.get('/api/tinypng/compressionCount', {
                headers: { Authorization: "Bearer " + token }
            })
            .then(function (resp) {
                app.compressionCount = resp.data;
            })
            .catch(function (resp) {
                alert('Could not get tinypng compression count')
            })
        },
        data() {
            return {
                compressionCount: null
            }
        }
    }
</script>
