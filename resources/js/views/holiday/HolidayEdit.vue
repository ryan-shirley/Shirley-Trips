<template>
    <div>
        <navigation></navigation>

        <section class="bg-primary page-title">
            <h1>Edit holiday</h1>
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
                <div class="form-group">
                    <label for="image">Image</label>
                    <br />
                    <img :src="imagePath" class="img-thumbnail" style="max-width: 200px" />
                    <input type="file" @change="uploadFieldChange">

                    <span class="badge badge-danger" v-text="form.errors.get('image')" v-if="form.errors.has('image')"></span>
                </div>

                <!-- <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="beginDate">Begin Date</label>

                            <input type="date" name="beginDate" class="form-control" v-model="form.beginDate" />

                            <span class="badge badge-danger" v-text="form.errors.get('beginDate')" v-if="form.errors.has('beginDate')"></span>
                        </div>
                    </div>
                   <div class="col-6">
                        <div class="form-group">
                            <label for="endDate">End Date</label>

                            <input type="date" name="endDate" class="form-control" v-model="form.endDate" />

                            <span class="badge badge-danger" v-text="form.errors.get('endDate')" v-if="form.errors.has('endDate')"></span>
                        </div>
                    </div>
                </div> -->


                <button type="submit" class="btn btn-primary">Submit</button>
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
                    // beginDate: '',
                    // endDate: ''
                }),
                imagePath: '',
                imageToUpload: ''
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

                if(app.imageToUpload) {
                    app.uploadImage()
                        .then(data => {
                            app.form.imageId = data.id

                            // Submit Main Form
                            this.form.submit()
                                .then(data => {
                                    app.$router.push({ name: 'holiday.view' })
                                })
                                .catch(errors => console.log(errors))
                                })
                }
                else {
                    // Submit Main Form
                    this.form.submit()
                        .then(data => {
                            app.$router.push({ name: 'holiday.view' })
                        })
                        .catch(errors => console.log(errors))
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