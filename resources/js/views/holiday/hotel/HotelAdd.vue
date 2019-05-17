<template>
    <div>
        <navigation></navigation>

        <section class="bg-primary page-title">
            <h1>Add Hotel</h1>
        </section>

        <section class="container">
            <form @submit.prevent="onSubmit" @keydown="form.errors.clear($event.target.name)">

                <div class="form-group">
                    <label for="name">Name</label>

                    <input type="text" name="name" class="form-control" v-model="form.name" />

                    <span class="badge badge-danger" v-text="form.errors.get('name')" v-if="form.errors.has('name')"></span>
                </div>
                <div class="form-group">
                    <label for="location">Location</label>

                    <input type="text" name="location" class="form-control" v-model="form.location" />

                    <span class="badge badge-danger" v-text="form.errors.get('location')" v-if="form.errors.has('location')"></span>
                </div>
                <div class="form-group">
                    <label for="image">Image</label>
                    <br />

                    <input type="file" @change="uploadFieldChange">

                    <span class="badge badge-danger" v-text="form.errors.get('image')" v-if="form.errors.has('image')"></span>
                </div>

                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label>Check In</label>
                            <select class="form-control" v-model="form.dayCheckInId">
                                <option  v-for="day in days" :key="'checkInDayId' + day.id" :value="day.id">{{ day.day }}</option>
                            </select>

                            <span class="badge badge-danger" v-text="form.errors.get('dayCheckInId')" v-if="form.errors.has('dayCheckInId')"></span>
                        </div>
                    </div>
                   <div class="col-6">
                        <div class="form-group">
                            <label>Check Out</label>
                            <select class="form-control" v-model="form.dayCheckOutId">
                                <option  v-for="day in days" :key="'dayCheckOutId' + day.id" :value="day.id">{{ day.day }}</option>
                            </select>

                            <span class="badge badge-danger" v-text="form.errors.get('dayCheckOutId')" v-if="form.errors.has('dayCheckOutId')"></span>
                        </div>
                    </div>
                </div>

                <button v-if="!loading" type="submit" class="btn btn-primary">Submit</button>

                <button v-if="loading" class="btn btn-primary" type="button" disabled>
                    <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                    Submitting...
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
                form: new Form('/hotels', 'post', true, {
                    name: '',
                    location: '',
                    image: '',
                    dayCheckInId: parseInt(this.$route.params.dayId),
                    dayCheckOutId: '',
                    holidayId: this.$route.params.holidayId
                }),
                days: [],
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
                app.days = resp.data.days
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

                let data = new FormData()
                data.append('name', app.form.name)
                data.append('location', app.form.location)
                data.append('image', app.form.image)
                data.append('dayCheckInId', app.form.dayCheckInId)
                data.append('dayCheckOutId', app.form.dayCheckOutId)
                data.append('holidayId', app.form.holidayId)

                axios.post('/api/hotels', data, {
                    headers: { 
                        Authorization: "Bearer " + token,
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then(function (resp) {
                    console.log(resp)
                    app.$router.push({name: 'holiday.view.day', params: { 'dayId': app.$route.params.dayId } })
                })
                .catch(function (resp) {
                    // alert('Could not save holiday')
                    console.log(resp)
                    app.form.errors.record(resp.response.data)
                    app.loading = false
                })
            },
            uploadFieldChange(event) {
                this.form.image = event.target.files[0]
            }
        },
    }
</script>