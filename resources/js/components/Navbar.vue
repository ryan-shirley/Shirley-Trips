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
                <button class="dropdown-item" type="button" @click="editModeToggle()">Edit</button>
                <div class="dropdown-divider"></div>
                <button class="dropdown-item" type="button">Delete Holiday</button>
            </div>
        </div>

        <div class="navbar-collapse offcanvas-collapse" v-bind:class="{ 'open': open }">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item" v-bind:class="{ 'active': checkActive('account.home') }">
                    <router-link :to="{ name: 'account.home' }" class="nav-link">Home</router-link>
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
                this.$router.push({ name: 'login' })
            }
        }
    }
  </script>