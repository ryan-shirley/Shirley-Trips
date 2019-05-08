<template>
    <div>
        <div class="card comment" v-if="comment.images.length == 1">
            <img :src="comment.images[0].path" class="card-img-top" :alt="comment.title">
            <div class="card-body">
                <h5 class="card-title">{{ comment.title }}</h5>
                <p class="card-text">{{ comment.subTitle }}</p>

                <div v-if="editMode" >
                    <router-link :to="{ name:'holiday.edit.message', params: { 'day' : dayId, 'commentId' : comment.id } }" class="btn btn-secondary">Edit</router-link>
                    <button type="button" class="btn btn-danger" @click="deleteComment(comment.id)">Delete</button>
                </div>
            </div>
        </div>
        <div class="card comment double" v-else-if="comment.images.length == 2">
            <div class="row no-gutters">
                <div v-for="image in comment.images" class="col-6" :key="'activity_image' + image.id">
                    <img :src="image.path" class="card-img" />
                </div>
            </div>
            <div class="card-body">
                <h5 class="card-title">{{ comment.title }}</h5>
                <p class="card-text">{{ comment.subTitle }}</p>

                <div v-if="editMode" >
                    <router-link :to="{ name:'holiday.edit.message', params: { 'day' : dayId, 'commentId' : comment.id } }" class="btn btn-secondary">Edit</router-link>
                    <button type="button" class="btn btn-danger" @click="deleteComment(comment.id)">Delete</button>
                </div>
            </div>
        </div>

        

        <div class="card comment" v-else>
            <div class="card-body">
                <h4>{{ comment.title }}</h4>
                <p class="card-text">{{ comment.subTitle }}</p>

                <div v-if="editMode" >
                    <router-link :to="{ name:'holiday.edit.message', params: { 'day' : dayId, 'commentId' : comment.id } }" class="btn btn-secondary">Edit</router-link>
                    <button type="button" class="btn btn-danger" @click="deleteComment(comment.id)">Delete</button>
                </div>
            </div>
            <div v-for="image in comment.images" :key="'activity_image' + image.id">
                <img :src="image.path" />
            </div>
        </div>
        <!--/.Comment -->
    </div>
</template>

<script>
    export default {
        name: 'comment-details',
        props: {
            comment: Object,
            editMode: Boolean,
            dayId: Number
        },
        data() {
            return {
            }
        },
        methods: {
            deleteComment(id) {
                if(confirm("Are you sure you want to delete this comment?")) {
                    let app = this
                    let token = localStorage.getItem('token')
                    
                    axios.delete('/api/comment/' + app.comment.id, {
                        headers: { Authorization: "Bearer " + token },
                        data: { 'activity_id': app.comment.activity_id }
                    })
                    .then(resp => {
                       app.$emit('commentDeleted', app.comment.activity_id)
                    })
                    .catch(error => alert("Could not delete comment"))
                }
            }
        }
    }
</script>
