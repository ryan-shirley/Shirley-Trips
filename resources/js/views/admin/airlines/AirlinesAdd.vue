<template>
    <div>
        <h3 class="text-center">Add Airline</h3>

        <section class="container">
            <router-link :to="{ name:'admin.airlines.list'}" class="btn btn-secondary">Cancel</router-link>
            
            <form @submit.prevent="onSubmit" @keydown="form.errors.clear($event.target.name)">

                <div class="form-group">
                    <label for="name">Name <span class="required">*</span></label>

                    <input type="text" name="name" class="form-control" v-model="form.name" required />

                    <span class="badge badge-danger" v-text="form.errors.get('name')" v-if="form.errors.has('name')"></span>
                </div>
                <div class="form-group">
                    <label for="image">Image <span class="required">*</span></label>
                    <br />
                    <input type="file" @change="uploadFieldChange">

                    <span class="badge badge-danger" v-text="form.errors.get('image')" v-if="form.errors.has('image')"></span>
                </div>

                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </section>
    </div>
</template>

<script>
    import Form from '../../../classes/Form.js'
    // Uploading Image
    import FormData from 'form-data'

    export default {
        data() {
            return {
                form: new Form('/airlines', 'post', true, {
                    name: '',
                    imageId: ''
                }),
                imageToUpload: ''
            }
        },
        methods: {
            onSubmit() {
                let app = this
                let token = localStorage.getItem('token')

                app.uploadImage()
                    .then(data => {
                        app.form.imageId = data.id

                        // Submit Main Form
                        this.form.submit()
                            .then(data => {
                                app.$router.push({ name: 'admin.airlines.list' })
                            })
                            .catch(errors => console.log(errors))
                    })
                
            },
            uploadFieldChange(event) {
                this.imageToUpload = event.target.files[0]
            },
            uploadImage() {
                let app = this
                let token = localStorage.getItem('token')
                
                let data = new FormData()
                data.append('image', app.imageToUpload)
                data.append('folder', 'airlines')

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
        }
    }
</script>
