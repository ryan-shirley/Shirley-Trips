<template>
    <div>
        <navigation></navigation>

        <section class="bg-primary page-title">
            <h1>Create A Holiday!</h1>
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

                    <input type="file" @change="uploadFieldChange">

                    <span class="badge badge-danger" v-text="form.errors.get('image')" v-if="form.errors.has('image')"></span>
                </div>

                <div class="row">
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
                </div>


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
                form: new Form('/holiday', 'post', true, {
                    title: '',
                    subTitle: '',
                    image: '',
                    beginDate: '',
                    endDate: ''
                }),
            }
        },
        methods: {
            onSubmit() {
                let app = this
                let token = localStorage.getItem('token')

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

                axios.post('/api/holiday/', data, {
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
                })
            },
            uploadFieldChange(event) {
                this.form.image = event.target.files[0]
            }
        },
    }
</script>