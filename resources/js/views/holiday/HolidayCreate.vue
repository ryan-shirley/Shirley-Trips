<template>
    <div>
        <navigation></navigation>

        <section class="bg-primary page-title">
            <h1>Create A Holiday!</h1>
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

                    <input type="file" @change="uploadFieldChange">

                    <span class="badge badge-danger" v-text="form.errors.get('image')" v-if="form.errors.has('image')"></span>
                </div>

                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="beginDate">Begin Date <span class="required">*</span></label>

                            <input type="date" name="beginDate" class="form-control" v-model="form.beginDate" />

                            <span class="badge badge-danger" v-text="form.errors.get('beginDate')" v-if="form.errors.has('beginDate')"></span>
                        </div>
                    </div>
                   <div class="col-6">
                        <div class="form-group">
                            <label for="endDate">End Date <span class="required">*</span></label>

                            <input type="date" name="endDate" class="form-control" v-model="form.endDate" />

                            <span class="badge badge-danger" v-text="form.errors.get('endDate')" v-if="form.errors.has('endDate')"></span>
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

        <footer-global />

    </div>
</template>

<script>
    import Form from '../../classes/Form.js'
    // Uploading Image
    import FormData from 'form-data'

    export default {
        data() {
            return {
                form: new Form('/holiday', 'post', true, {
                    title: '',
                    subTitle: '',
                    image: '',
                    beginDate: '',
                    endDate: ''
                }),
                loading: false
            }
        },
        methods: {
            onSubmit() {
                let app = this
                let token = localStorage.getItem('token')
                app.loading = true

                // Ensure check out is after checkin
                if(app.form.beginDate >= app.form.endDate) {
                    console.log('Begin is before end')
                    app.form.errors.record({
                        'endDate': [
                            "End date must be after begin date"
                        ]
                    })
                    app.loading = false
                    return;
                }

                // this.form.submit()
                //     .then(data => {
                //         app.$router.push({name: 'holiday.view.day', params: { 'editMode': true } })
                //     })
                //     .catch(errors => console.log(errors))

                let data = new FormData()
                data.append('title', app.form.title)
                data.append('subTitle', app.form.subTitle)
                data.append('image', app.form.image)
                data.append('beginDate', app.form.beginDate)
                data.append('endDate', app.form.endDate)

                axios.post('/api/holiday', data, {
                    headers: { 
                        Authorization: "Bearer " + token,
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then(function (resp) {
                    console.log(resp)
                    app.$router.push({name: 'holiday.view', params: { 'holidayId': resp.data.id } })
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