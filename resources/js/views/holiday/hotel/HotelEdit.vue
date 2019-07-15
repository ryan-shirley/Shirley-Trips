<template>
    <div>
        <navigation />

        <section class="bg-primary page-title">
            <h1>Edit Hotel</h1>
        </section>

        <section class="container">
            <form @submit.prevent="onSubmit" @keydown="form.errors.clear($event.target.name)">

                <div class="form-group">
                    <label for="name">Name <span class="required">*</span></label>

                    <input type="text" name="name" class="form-control" v-model="form.name" />

                    <span class="badge badge-danger" v-text="form.errors.get('name')" v-if="form.errors.has('name')"></span>
                </div>
                <div class="form-group">
                    <label for="location">Location <span class="required">*</span></label>

                    <input type="text" name="location" class="form-control" v-model="form.location" />

                    <span class="badge badge-danger" v-text="form.errors.get('location')" v-if="form.errors.has('location')"></span>
                </div>
                <div class="form-group">
                    <label for="image">Image <span class="required">*</span></label>
                    <br />
                    <img :src="imagePath" class="img-thumbnail" style="max-width: 200px" />
                    <input type="file" @change="uploadFieldChange">

                    <span class="badge badge-danger" v-text="form.errors.get('image')" v-if="form.errors.has('image')"></span>
                </div>

                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label>Check In <span class="required">*</span></label>
                            <select class="form-control" v-model="form.dayCheckInId">
                                <option  v-for="day in days" :key="'checkInDayId' + day.id" :value="day.id">{{ day.day }}</option>
                            </select>

                            <span class="badge badge-danger" v-text="form.errors.get('dayCheckInId')" v-if="form.errors.has('dayCheckInId')"></span>
                        </div>
                    </div>
                   <div class="col-6">
                        <div class="form-group">
                            <label>Check Out <span class="required">*</span></label>
                            <select class="form-control" v-model="form.dayCheckOutId">
                                <option  v-for="day in days" :key="'dayCheckOutId' + day.id" :value="day.id">{{ day.day }}</option>
                            </select>

                            <span class="badge badge-danger" v-text="form.errors.get('dayCheckOutId')" v-if="form.errors.has('dayCheckOutId')"></span>
                        </div>
                    </div>
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
    // Uploading Image
    import FormData from 'form-data'
    import { parse } from 'path';

    export default {
        data() {
            return {
                form: new Form('/hotels/' + this.$route.params.hotelId, 'put', true, {
                    name: '',
                    location: '',
                    imageId: '',
                    dayCheckInId: '',
                    dayCheckOutId: '',
                    holidayId: this.$route.params.holidayId
                }),
                days: [],
                imagePath: '',
                imageToUpload: '',
                loading: false
            }
        },
        mounted() {
            let app = this
            let token = localStorage.getItem('token')
            let holidayId = app.$route.params.holidayId

            // Load individual holiday
            axios.get('/api/holiday/' + holidayId, {
                headers: { Authorization: "Bearer " + token }
            })
            .then(resp => {
                app.days = resp.data.holiday.days


                // Load Hotel
                axios.get('/api/hotels/' + this.$route.params.hotelId, {
                    headers: { Authorization: "Bearer " + token }
                })
                .then(resps => {
                    app.form.name = resps.data.name
                    app.form.location = resps.data.location
                    app.imagePath = resps.data.image.path

                    for (var i = 0; i < app.days.length; i++) {
                        let day = app.days[i]

                        if(day.day == resps.data.checkIn) {
                            app.form.dayCheckInId = day.id
                        }
                        else if (day.day == resps.data.checkOut) {
                            app.form.dayCheckOutId = day.id
                        }
                    }
                })
                .catch(errors => {
                    // alert('Could not load hotel')
                    console.log(errors)
                })
            })
            .catch(errors => alert('Could not load holiday days'))
        },
        methods: {
            onSubmit() {
                let app = this
                let token = localStorage.getItem('token')
                app.loading = true

                // Ensure check out is after checkin
                if(app.form.dayCheckInId >= app.form.dayCheckOutId) {
                    console.log('Check out is before check in')
                    app.form.errors.record({
                        'dayCheckOutId': [
                            "Check out must be after check in"
                        ]
                    })
                    app.loading = false
                    return;
                }

                if(app.imageToUpload) {
                    // New image to upload
                    app.uploadImage()
                        .then(data => {
                            app.form.imageId = data.id

                            // Submit Main Form
                            this.form.submit()
                                .then(data => {
                                    app.$router.push({name: 'holiday.view.day', params: { 'dayId': app.$route.params.dayId } })
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
                            app.$router.push({name: 'holiday.view.day', params: { 'dayId': app.$route.params.dayId } })
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
                data.append('folder', 'hotels')

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