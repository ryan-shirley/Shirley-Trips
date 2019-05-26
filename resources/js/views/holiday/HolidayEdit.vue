<template>
    <div>
        <navigation></navigation>

        <section class="bg-primary page-title">
            <h1>Edit holiday</h1>
        </section>

        <section class="container">
            <form @submit.prevent="onSubmit" @keydown="form.errors.clear($event.target.name)">

                <div class="form-group">
                    <label for="title">Title <span class="required">*</span></label>

                    <input type="text" name="title" class="form-control" v-model="form.title" />

                    <span class="badge badge-danger" v-text="form.errors.get('title')" v-if="form.errors.has('title')"></span>
                </div>
                <div class="form-group">
                    <label for="subTitle">Sub Title <span class="required">*</span></label>

                    <input type="text" name="title" class="form-control" v-model="form.subTitle" />

                    <span class="badge badge-danger" v-text="form.errors.get('subTitle')" v-if="form.errors.has('subTitle')"></span>
                </div>
                <div class="form-group">
                    <label for="image">Image <span class="required">*</span></label>
                    <br />
                    <img :src="imagePath" class="img-thumbnail" style="max-width: 200px" />
                    <input type="file" @change="uploadFieldChange">

                    <span class="badge badge-danger" v-text="form.errors.get('image')" v-if="form.errors.has('image')"></span>
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
    import Form from '../../classes/Form.js'
    // Uploading Image
    import FormData from 'form-data'

    export default {
        data() {
            return {
                form: new Form('/holiday/' + this.$route.params.holidayId, 'put', true, {
                    title: '',
                    subTitle: '',
                    imageId: '',
                }),
                imagePath: '',
                imageToUpload: '',
                loading: false
            }
        },
        mounted() {
            let app = this
            let token = localStorage.getItem('token')
            let holidayId = app.$route.params.holidayId

            // Load individual course
            axios.get('/api/holiday/' + holidayId, {
                headers: { Authorization: "Bearer " + token }
            })
            .then(resp => {
                app.form.title = resp.data.title
                app.form.subTitle = resp.data.subTitle
                app.form.beginDate = resp.data.beginDate
                app.form.endDate = resp.data.endDate
                app.imagePath = resp.data.image.path
            })
            .catch(errors => alert('Could not load holiday'))
        },
        methods: {
            onSubmit() {
                let app = this
                let token = localStorage.getItem('token')
                app.loading = true

                if(app.imageToUpload) {
                    app.uploadImage()
                        .then(data => {
                            app.form.imageId = data.id

                            // Submit Main Form
                            this.form.submit()
                                .then(data => {
                                    app.$router.push({ name: 'holiday.view' })
                                })
                                .catch(errors => {
                                    console.log(errors)
                                    app.loading = false
                                })
                        })
                }
                else {
                    // Submit Main Form
                    this.form.submit()
                        .then(data => {
                            app.$router.push({ name: 'holiday.view' })
                        })
                        .catch(errors => {
                        console.log(errors)
                        app.loading = false
                    })
                }

            },
            uploadFieldChange(event) {
                this.imageToUpload = event.target.files[0]
            },
            uploadImage() {
                let app = this
                let token = localStorage.getItem('token')
                
                let data = new FormData()
                data.append('image', app.imageToUpload)
                data.append('folder', 'holidays')

                return new Promise((resolve, reject) => {
                    axios.post('/api/images', data, {
                            headers: { 
                                Authorization: "Bearer " + token,
                                'Content-Type': 'multipart/form-data'
                            }
                        })
                        .then(response => resolve(response.data) )
                        .catch(error => {
                            app.form.errors.record(error.response.data)
                            reject(error.response.data)
                        })
                })
            }
        },
    }
</script>