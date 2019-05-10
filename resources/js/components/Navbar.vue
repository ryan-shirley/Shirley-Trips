<template>
    <nav class="navbar navbar-expand-lg fixed-top navbar-light bg-white navbar-offcanvas">
        <button class="navbar-toggler d-block" type="button" @click="toggleNav">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <router-link to="/" class="navbar-brand">E &amp; R</router-link>

        <div class="btn-group" v-if="editPermissions">
            <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                ...
            </button>
            <div class="dropdown-menu dropdown-menu-right">
                <button class="dropdown-item" type="button" @click="editModeToggle()">Toggle Edit Mode</button>
                <div class="dropdown-divider"></div>
                <router-link :to="{ name:'holiday.edit', params: { 'holidayId' :$route.params.holidayId } }" class="dropdown-item">Edit Holiday</router-link>
                <div class="dropdown-divider"></div>
                <button class="dropdown-item" type="button" @click="deleteHoliday()">Delete Holiday</button>
            </div>
        </div>

        <div class="navbar-collapse offcanvas-collapse" v-bind:class="{ 'open': open }">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item" v-bind:class="{ 'active': checkActive('account.home') }">
                    <router-link :to="{ name: 'account.home' }" class="nav-link">Home</router-link>
                </li>
                <li class="nav-item" v-bind:class="{ 'active': checkActive('holiday.create') }">
                    <router-link :to="{ name:'holiday.create'}" class="nav-link">Create Holiday</router-link>
                </li>
                <li class="nav-item" v-bind:class="{ 'active': checkActive('admin.home') }">
                    <router-link :to="{ name:'admin.home'}" class="nav-link">Admin Dashboard</router-link>
                </li>
               
                <li class="nav-item">
                    <button @click="logout()" class="nav-link">Logout</button>
                </li>
            </ul>
        </div>
    </nav>
    <!--  END NAVBAR   -->
</template>

<script>

    export default {
        name: 'navigation',
        props: {
            editPermissions: {
                type: Boolean,
                default: false
            },
        },
        data() {
            return {
                open: false,
            }
        },
        methods: {
            toggleNav() {
                this.open = !this.open;
            },
            editModeToggle() {
                this.$emit('editModeToggle')  
            },
            checkActive(name) {
                if(this.$route.name == name) {
                    return true
                }
                return false
            },
            logout() {
                let token = localStorage.removeItem('token')
                let isAdmin = localStorage.removeItem('isAdmin')
                this.$router.push({ name: 'login' })
            },
            deleteHoliday(id) {
                if(confirm("Are you sure you want to delete this holiday?")) {
                    let app = this
                    let token = localStorage.getItem('token')
                    
                    axios.delete('/api/holiday/' + this.$route.params.holidayId, {
                        headers: { Authorization: "Bearer " + token }
                    })
                    .then(resp => {
                        app.$router.push({ name: 'account.home' })
                    })
                    .catch(error => alert("Could not delete holiday"))
                }
            }
        }
    }
  </script>