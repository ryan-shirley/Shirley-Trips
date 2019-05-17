<template>
    <div>
        <navigation></navigation>

        <section class="bg-primary page-title">
                <h1>Edit Video</h1>
        </section>

        <section class="container">
            <form @submit.prevent="onSubmit" @keydown="form.errors.clear($event.target.name)">

                <div class="form-group">
                    <label for="videoUrl">Url</label>

                    <input type="text" name="videoUrl" class="form-control" v-model="form.videoUrl" />

                    <span class="badge badge-danger" v-text="form.errors.get('videoUrl')" v-if="form.errors.has('videoUrl')"></span>
                </div>

                <button v-if="!loading" type="submit" class="btn btn-primary">Update</button>

                <button v-if="loading" class="btn btn-primary" type="button" disabled>
                    <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                    Updating...
                </button>
            </form>
        </section>
    </div>
</template>

<script>
    import Form from '../../../classes/Form.js'

    export default {
        data() {
            return {
                form: new Form('/videos/' + this.$route.params.videoId, 'put', true, {
                    videoUrl: ''
                }),
                loading: false
            }
        },
        mounted() {
            let app = this
            let token = localStorage.getItem('token')

            // Load Video
            axios.get('/api/videos/' + this.$route.params.videoId, {
                headers: { Authorization: "Bearer " + token }
            })
            .then(resp => {
                app.form.videoUrl = resp.data.url
            })
            .catch(errors => alert('Could not load video'))
        },
        methods: {
            onSubmit() {
                let app = this
                app.loading = true

                this.form.submit()
                    .then(data => {
                        app.$router.push({name: 'holiday.view.day'})
                    })
                    .catch(errors => {
                        console.log(errors)
                        app.loading = false
                    })
            }
        }
    }
</script>
