<template>
    <div>
        <navigation></navigation>

        <section class="bg-primary page-title">
                <h1>Edit Message</h1>
        </section>

        <section class="container">
            <form @submit.prevent="onSubmit" @keydown="form.errors.clear($event.target.name)">

                <div class="form-group">
                    <label for="title">Title</label>

                    <input type="text" name="title" class="form-control" v-model="form.title" />

                    <span class="badge badge-danger" v-text="form.errors.get('title')" v-if="form.errors.has('title')"></span>
                </div>
                <div class="form-group">
                    <label for="subTitle">Sub Title</label>

                    <input type="text" name="title" class="form-control" v-model="form.subTitle" />

                    <span class="badge badge-danger" v-text="form.errors.get('subTitle')" v-if="form.errors.has('subTitle')"></span>
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </section>
    </div>
</template>

<script>
    import Form from '../../../classes/Form.js'

    export default {
        data() {
            return {
                form: new Form('/comment/' + this.$route.params.commentId, 'put', true, {
                    title: '',
                    subTitle: '',
                    dayId: this.$route.params.dayId
                }),
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
                app.form.title = resp.data.title
                app.form.subTitle = resp.data.subTitle
            })
            .catch(errors => alert('Could not load comment'))
        },
        methods: {
            onSubmit() {
                let app = this

                this.form.submit()
                    .then(data => {
                        app.$router.push({name: 'holiday.view.day', params: { 'editMode': true } })
                    })
                    .catch(errors => console.log(errors))
            }
        }
    }
</script>
