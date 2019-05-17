<template>
    <div class="card" v-if="reOrderMode">
        <div class="card-body">
            <div class="video">
                <iframe :src="video.url" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
        </div>
    </div>
    <div class="card" v-else>
        <div class="video">
            <iframe :src="video.url" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>
        <div class="row">
            <br />
            <div class="col">
                <router-link :to="{ name:'holiday.edit.video', params: { 'day' : dayId, 'videoId' : video.id } }" class="btn btn-secondary btn-block">Edit</router-link>
            </div>
            <div class="col">
                <button type="button" class="btn btn-danger btn-block" @click="deleteVideo(video.id)">Delete</button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'video-card',
        props: {
            video: Object,
            reOrderMode: Boolean,
            dayId: Number
        },
        methods: {
            deleteVideo(id) {
                if(confirm("Are you sure you want to delete this video?")) {
                    let app = this
                    let token = localStorage.getItem('token')
                    
                    axios.delete('/api/videos/' + app.video.id, {
                        headers: { Authorization: "Bearer " + token },
                        data: { 'activity_id': app.video.activity_id }
                    })
                    .then(resp => {
                       app.$emit('videoDeleted', app.video.activity_id)
                    })
                    .catch(error => alert("Could not delete video"))
                }
            }
        },
    }
</script>
<style scoped>
    .video { 
        position: relative; 
        padding-bottom: 56.25%; 
        height: 0; 
        overflow: hidden; 
        max-width: 100%; 
        border-radius: 10px;
    } 
    .video iframe, .video object, .video embed { 
        position: absolute; 
        top: 0; 
        left: 0; 
        width: 100%; 
        height: 100%; 
    }
</style>