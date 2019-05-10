<template>
    <div>
        <navigation></navigation>

        <section class="bg-primary page-title">
                <h1>Add Message</h1>
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
                form: new Form('/comment', 'post', true, {
                    title: '',
                    subTitle: '',
                    dayId: this.$route.params.dayId
                }),
            }
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
