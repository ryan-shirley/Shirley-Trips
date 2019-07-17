<template>
    <div>
        <navigation :backArrow='true'></navigation>

        <section class="bg-primary page-title">
                <h1>Add Video</h1>
        </section>

        <section class="container">
            <form @submit.prevent="onSubmit" @keydown="form.errors.clear($event.target.name)">

                <div class="form-group">
                    <label for="videoUrl">Url <span class="required">*</span></label>

                    <input type="text" name="videoUrl" class="form-control" v-model="form.videoUrl" />

                    <span class="badge badge-danger" v-text="form.errors.get('videoUrl')" v-if="form.errors.has('videoUrl')"></span>
                </div>
                

                <button v-if="!loading" type="submit" class="btn btn-primary">Submit</button>

                <button v-if="loading" class="btn btn-primary" type="button" disabled>
                    <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                    Submitting...
                </button>
            </form>
        </section>

        <footer-global />
    </div>
</template>

<script>
    import Form from '../../../classes/Form.js'

    export default {
        data() {
            return {
                form: new Form('/videos', 'post', true, {
                    videoUrl: '',
                    dayId: this.$route.params.dayId
                }),
                loading: false
            }
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
