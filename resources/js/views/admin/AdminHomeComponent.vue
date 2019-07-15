<template>
    <div>
        <navigation></navigation>

        <section class="bg-primary page-title">
            <h1>Admin Dashboard</h1>
        </section>

        <section class="container">
            <ul class="nav nav-pills nav-fill mb-5">
                <li class="nav-item">
                    <router-link :to="{ name:'admin.airlines.list'}" class="nav-link" v-bind:class="{ 'active': checkActive('admin.airlines.list') }">Airlines</router-link>
                </li>
                <li class="nav-item">
                    <router-link :to="{ name:'admin.tinypng'}" class="nav-link" v-bind:class="{ 'active': checkActive('admin.tinypng') }">Tiny PNG</router-link>
                </li>
            </ul>
        </section>

        <section class="container">
            <router-view></router-view>
        </section>

        <footer-global />

    </div>
</template>

<script>
    export default {
        mounted() {
        },
        data() {
            return {
            }
        },
        methods: {
            checkActive(name) {
                if(this.$route.name == name) {
                    return true
                }
                return false
            },
        }
    }
</script>
