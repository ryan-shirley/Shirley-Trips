<template>
    <div>
        <navigation></navigation>

        <section class="bg-primary page-title">
                <h1>Add Flight</h1>
        </section>

        <section class="container">
            <form @submit.prevent="onSubmit" @keydown="form.errors.clear($event.target.name)">

                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="airline">Airline</label>

                            <select class="form-control" v-model="form.airlineId">
                                <option v-for="airline in airlines" :value="airline.id" :key="airline.id">
                                    {{ airline.name }}
                                </option>
                            </select>

                            <span class="badge badge-danger" v-text="form.errors.get('airlineId')" v-if="form.errors.has('airlineId')"></span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="flightNumber">Flight Number</label>

                            <input type="text" name="flightNumber" class="form-control" v-model="form.flightNumber" />

                            <span class="badge badge-danger" v-text="form.errors.get('flightNumber')" v-if="form.errors.has('flightNumber')"></span>
                        </div>
                    </div>
                </div>

                <hr />

                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="originDate">Origin Date</label>

                            <input type="date" name="originDate" class="form-control" v-model="form.originDate" />

                            <span class="badge badge-danger" v-text="form.errors.get('originDate')" v-if="form.errors.has('originDate')"></span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="originTime">Origin Time</label>

                            <input type="time" name="originTime" class="form-control" v-model="form.originTime" />

                            <span class="badge badge-danger" v-text="form.errors.get('originTime')" v-if="form.errors.has('originTime')"></span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="originAirportShort">Origin Airport Short</label>

                            <input type="text" name="originAirportShort" class="form-control" v-model="form.originAirportShort" />

                            <span class="badge badge-danger" v-text="form.errors.get('originAirportShort')" v-if="form.errors.has('originAirportShort')"></span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="originAirportLong">Origin Airport Long</label>

                            <input type="text" name="originAirportLong" class="form-control" v-model="form.originAirportLong" />

                            <span class="badge badge-danger" v-text="form.errors.get('originAirportLong')" v-if="form.errors.has('originAirportLong')"></span>
                        </div>
                    </div>
                </div>

                <hr />

                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="destinationDate">Destination Date</label>

                            <input type="date" name="destinationDate" class="form-control" v-model="form.destinationDate" />

                            <span class="badge badge-danger" v-text="form.errors.get('destinationDate')" v-if="form.errors.has('destinationDate')"></span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="destinationTime">Destination Time</label>

                            <input type="time" name="destinationTime" class="form-control" v-model="form.destinationTime" />

                            <span class="badge badge-danger" v-text="form.errors.get('destinationTime')" v-if="form.errors.has('destinationTime')"></span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="destinationAirportShort">Destination Airport Short</label>

                            <input type="text" name="destinationAirportShort" class="form-control" v-model="form.destinationAirportShort" />

                            <span class="badge badge-danger" v-text="form.errors.get('destinationAirportShort')" v-if="form.errors.has('destinationAirportShort')"></span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="destinationAirportLong">Destination Airport Long</label>

                            <input type="text" name="destinationAirportLong" class="form-control" v-model="form.destinationAirportLong" />

                            <span class="badge badge-danger" v-text="form.errors.get('destinationAirportLong')" v-if="form.errors.has('destinationAirportLong')"></span>
                        </div>
                    </div>
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
                form: new Form('/flight/' +  this.$route.params.flightId, 'put', true, {
                    airlineId: '',
                    flightNumber: '',
                    originDate: '',
                    originTime: '',
                    originAirportShort: '',
                    originAirportLong: '',
                    destinationDate: '',
                    destinationTime: '',
                    destinationAirportShort: '',
                    destinationAirportLong: '',
                    dayId: this.$route.params.day
                }),
                airlines: []
            }
        },
        mounted() {
            let app = this
            let token = localStorage.getItem('token')

            // Load Airlines
            axios.get('/api/airlines/', {
                headers: { Authorization: "Bearer " + token }
            })
            .then(resp => {
                app.airlines = resp.data
            })
            .catch(errors => alert('Could not load airline'))

            // Load Flight
            axios.get('/api/flight/' + this.$route.params.flightId, {
                headers: { Authorization: "Bearer " + token }
            })
            .then(resp => {
                app.form.airlineId = resp.data.airline_id
                app.form.flightNumber = resp.data.flightNumber
                app.form.originAirportShort = resp.data.originAirportShort
                app.form.originAirportLong = resp.data.originAirportLong
                app.form.destinationAirportShort = resp.data.destinationAirportShort
                app.form.destinationAirportLong = resp.data.destinationAirportLong

                // Convert Day Times
                let originDayTime = new Date(resp.data.originDayTime)
                app.form.originDate = originDayTime.getFullYear() + "-" + app.appendLeadingZeroes(originDayTime.getMonth() + 1) + "-" + app.appendLeadingZeroes(originDayTime.getDate());
                app.form.originTime = app.appendLeadingZeroes(originDayTime.getHours()) + ":" + app.appendLeadingZeroes(originDayTime.getMinutes())

                let destinationDayTime = new Date(resp.data.destinationDayTime)
                app.form.destinationDate = destinationDayTime.getFullYear() + "-" + app.appendLeadingZeroes(destinationDayTime.getMonth() + 1) + "-" + app.appendLeadingZeroes(destinationDayTime.getDate());
                app.form.destinationTime = app.appendLeadingZeroes(destinationDayTime.getHours()) + ":" + app.appendLeadingZeroes(destinationDayTime.getMinutes())
            })
            .catch(errors => alert('Could not load flight'))
        },
        methods: {
            onSubmit() {
                let app = this

                this.form.submit()
                    .then(data => {
                        app.$router.push({name: 'holiday.view', params: { 'editMode': true, 'dayStartPosition' : app.form.dayId } })
                    })
                    .catch(errors => console.log(errors))
            },
            appendLeadingZeroes(n){
                if(n <= 9){
                    return "0" + n;
                }
                return n
            }
        }
    }
</script>
