<template>
    <div>
        <navigation :backArrow='true'></navigation>

        <section class="bg-primary page-title">
                <h1>Add Flight</h1>
        </section>

        <section class="container">
            <form @submit.prevent="onSubmit" @keydown="form.errors.clear($event.target.name)">

                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="airline">Connecting Flight <span class="required">*</span></label>
                            <br />
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" v-model="isConnectingFlight" name="isConnectingFlight" :value="false" checked>
                                <label class="form-check-label" for="no">No</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" v-model="isConnectingFlight" name="isConnectingFlight" :value="true">
                                <label class="form-check-label" for="yes">Yes</label>
                            </div>
                            
                        </div>
                    </div>
                </div>

                <div class="row" v-if="isConnectingFlight">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="airline">Previous Flight <span class="required">*</span></label>

                            <select class="form-control" v-model="form.connectingFlightId">
                                <option v-for="flight in previousFlights" :value="flight.id" :key="flight.id">
                                    {{ flight.originAirportShort }} - {{ flight.destinationAirportShort }}
                                </option>
                            </select>

                            <span class="badge badge-danger" v-text="form.errors.get('connectingFlightId')" v-if="form.errors.has('connectingFlightId')"></span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="layoverLength">Layover Length (mins) <span class="required">*</span></label>

                            <input type="text" name="flightNumber" class="form-control" v-model="form.layoverLength" />

                            <span class="badge badge-danger" v-text="form.errors.get('layoverLength')" v-if="form.errors.has('layoverLength')"></span>
                        </div>
                    </div>
                </div>

                <hr />

                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="airline">Airline <span class="required">*</span></label>

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
                            <label for="flightNumber">Flight Number <span class="required">*</span></label>

                            <input type="text" name="flightNumber" class="form-control" v-model="form.flightNumber" />

                            <span class="badge badge-danger" v-text="form.errors.get('flightNumber')" v-if="form.errors.has('flightNumber')"></span>
                        </div>
                    </div>
                </div>

                <hr />

                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="originDate">Origin Date <span class="required">*</span></label>

                            <input type="date" name="originDate" class="form-control" v-model="form.originDate" />

                            <span class="badge badge-danger" v-text="form.errors.get('originDate')" v-if="form.errors.has('originDate')"></span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="destinationDate">Destination Date <span class="required">*</span></label>

                            <input type="date" name="destinationDate" class="form-control" v-model="form.destinationDate" />

                            <span class="badge badge-danger" v-text="form.errors.get('destinationDate')" v-if="form.errors.has('destinationDate')"></span>
                        </div>
                    </div>
                </div>

                <span class="badge badge-danger" v-text="flightsApiError" v-if="flightsApiError"></span><br />
                
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
                form: new Form('/flight', 'post', true, {
                    airlineId: '',
                    flightNumber: '',
                    originDate: this.$route.params.dayString,
                    destinationDate: '',
                    connectingFlightId: '',
                    layoverLength: '',
                    dayId: this.$route.params.dayId
                }),
                isConnectingFlight: false,
                airlines: [],
                previousFlights: [],
                loading: false,
                flightsApiError: ''
            }
        },
        mounted() {
            let app = this
            let token = localStorage.getItem('token')

            // Load Airlines
            axios.get('/api/airlines', {
                headers: { Authorization: "Bearer " + token }
            })
            .then(resp => {
                app.airlines = resp.data
            })
            .catch(errors => alert('Could not load airline'))
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
                        app.flightsApiError = errors[1]

                        app.loading = false
                    })
            }
        },
        watch: {
            isConnectingFlight: function (connecting) {
            let app = this
            let token = localStorage.getItem('token')

                if(connecting && (this.previousFlights != undefined || this.previousFlights.length == 0)) {
                    console.log('Loading connecting flights')
                    // Load Flights for a day
                    axios.get('/api/day/' + app.$route.params.dayId + '/flights', {
                        headers: { Authorization: "Bearer " + token }
                    })
                    .then(resp => {
                        app.previousFlights = resp.data
                    })
                    .catch(errors => alert('Could not load flights'))
                }
            },
        }
    }
</script>
