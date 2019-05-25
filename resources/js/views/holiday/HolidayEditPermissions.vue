<template>
    <div>
        <navigation></navigation>

        <section class="bg-primary page-title">
            <h1>Edit Permissions</h1>
        </section>

        <section class="container">
            <div class="row justify-content-md-center">
                <div class="col-6">
                    <div class="card">
                        <div class="card-header">
                            <h2>All users</h2>
                        </div>
                        <div class="card-body">
                            <form @submit.prevent="onSubmit" @keydown="form.errors.clear($event.target.name)">
                                <table class="table table-striped table-bordered">
                                    <thead class="thead-light">
                                        <tr>
                                            <th scope="col">Name</th>
                                            <th scope="col">View</th>
                                            <th scope="col">Edit</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(user, index) in form.users" :key="user.id">
                                            <th scope="row">{{ user.name }}</th>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" v-model="form.users[index].can_view">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" v-model="form.users[index].can_edit">
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <button v-if="!loading" type="submit" class="btn btn-primary">Update</button>

                                <button v-if="loading" class="btn btn-primary" type="button" disabled>
                                    <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                                    Updating...
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>
</template>

<script>
    import Form from '../../classes/Form.js'

    export default {
        data() {
            return {
                userId: '',
                form: new Form('/holiday/' +  this.$route.params.holidayId + '/permissions', 'put', true, {
                    users: []
                }),
                users: [],
                loading: false
            }
        },
        mounted() {
            let app = this
            let token = localStorage.getItem('token')
            let holidayId = app.$route.params.holidayId

            // Load user logged in
            axios.get('/api/user', {
                headers: { Authorization: "Bearer " + token }
            })
            .then(resp => {
                app.userId = resp.data.user.id

                // Load user logged in
                axios.get('/api/users', {
                    headers: { Authorization: "Bearer " + token }
                })
                .then(resp => {
                    app.users = resp.data

                    let allUsers = app.usersWithoutLoggedIn
                    for (var i = 0; i < allUsers.length; i++) {
                        let userHolidays = allUsers[i].holidays

                        // Create user
                        let u = {
                            'id': allUsers[i].id,
                            'name': allUsers[i].first_name + ' ' + allUsers[i].last_name,
                            'can_view': false,
                            'can_edit': false
                        }

                        // Check if user has any holidays
                        if(userHolidays.length > 0) {

                            // Loop through users holidays for permissions
                            for (var i2 = 0; i2 < userHolidays.length; i2++) {
                                let holiday = userHolidays[i2]

                                console.log(holiday)

                                // Ensure holiday is same as being viewed
                                if(holiday.id == holidayId) {
                                    u.can_view = true

                                    if(holiday.pivot.editPermission) {
                                        u.can_edit = true
                                    }
                                }
                            }
                        }
                        
                        app.form.users.push(u)
                    }
                })
                .catch(errors => alert('Could not load users'))
            })
            .catch(errors => alert('Could not load user'))
        },
        computed: {
            usersWithoutLoggedIn() {
                let app = this
                let users = app.users

                for (var i = 0; i < users.length; i++) {
                    if(users[i].id == app.userId) {
                        users.splice(i, 1);
                        return users;
                    }
                }

                return null;
            }
        },
        methods: {
            onSubmit() {
                let app = this
                let token = localStorage.getItem('token')
                app.loading = true

                this.form.submit()
                    .then(data => {
                        app.$router.push({ name: 'holiday.view' })
                    })
                    .catch(errors => {
                        console.log(errors)
                        app.loading = false
                    })
            },
        }
    }
</script>