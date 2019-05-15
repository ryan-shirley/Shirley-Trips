<template>
    <div>
        <navigation></navigation>

        <section class="bg-primary page-title">
                <h1>Add Video</h1>
        </section>

        <section class="container">
            <form @submit.prevent="onSubmit" @keydown="form.errors.clear($event.target.name)">

                <div class="form-group">
                    <label for="videoUrl">Url</label>

                    <input type="text" name="videoUrl" class="form-control" v-model="form.videoUrl" />

                    <span class="badge badge-danger" v-text="form.errors.get('videoUrl')" v-if="form.errors.has('videoUrl')"></span>
                </div>
                

                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </section>
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
                })
            }
        },
        methods: {
            onSubmit() {
                let app = this

                this.form.submit()
                    .then(data => {
                        app.$router.push({name: 'holiday.view.day'})
                    })
                    .catch(errors => console.log(errors))
            }
        }
    }
</script>
