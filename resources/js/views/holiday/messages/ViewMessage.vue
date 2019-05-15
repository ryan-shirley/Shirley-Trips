<template>
    <div class="comment">
        <navigation
            :viewing='true'
            v-on:edit="switchToEditMessage"
            v-on:delete="deleteMessage"
        />

        <section class="bg-primary page-title">
                <h1>{{ message.title }}</h1>
                <p>{{ message.subTitle }}</p>
        </section>

        <section class="container">
             <div class="row justify-content-md-center">
                <div class="col-12 col-md-8">

                    <div class="card" v-if="message.description">
                        <div class="card-body">
                            <p class="description">{{ message.description }}</p>
                        </div>
                    </div>

                    <div v-for="image in orderedImages" :key="image.path" class="card image-only">
                        <img :src="image.path" class="img-fluid" :alt="message.title">
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                message: {},
                activity_id: ''
            }
        },
        mounted() {
            let app = this
            let token = localStorage.getItem('token')
            let commentId = app.$route.params.commentId

            // Load individual course
            axios.get('/api/comment/' + commentId, {
                headers: { Authorization: "Bearer " + token }
            })
            .then(resp => {
                app.message = resp.data
                app.activity_id = resp.data.activity.id
            })
            .catch(errors => alert('Could not load comment'))
        },
        computed: {
            orderedImages: function () {
                return _.orderBy(this.message.images, 'order')
            }
        },
        methods: {
            switchToEditMessage() {
                this.$router.push({name: 'holiday.edit.message', params: { 'dayId': this.$route.params.dayId, 'commentId': this.message.id } })
            },
            deleteMessage() {
                if(confirm("Are you sure you want to delete this comment?")) {
                    let app = this
                    let token = localStorage.getItem('token')
                    
                    axios.delete('/api/comment/' + app.message.id, {
                        headers: { Authorization: "Bearer " + token },
                        data: { 'activity_id': app.activity_id }
                    })
                    .then(resp => {
                        app.$router.push({name: 'holiday.view.day', params: { 'dayId': app.$route.params.dayId } })
                    })
                    .catch(error => alert("Could not delete comment"))
                }
            }
        }
    }
</script>
