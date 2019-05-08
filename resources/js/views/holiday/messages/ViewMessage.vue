<template>
    <div>
        <navigation></navigation>

        <section class="bg-primary page-title">
                <h1>{{ message.title }}</h1>
                <p>{{ message.subTitle }}</p>
        </section>

        <section class="container">
             <div class="row justify-content-md-center">
                <div class="col-12 col-md-8">
                    <div v-for="image in message.images" :key="image.path" class="card image-only">
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
                message: {}
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
            })
            .catch(errors => alert('Could not load comment'))
        },
        methods: {
        }
    }
</script>
