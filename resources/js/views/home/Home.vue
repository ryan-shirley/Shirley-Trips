<template>
    <section class="position-relative">
        <carousel
            :nav="false"
            :center="true"
            :dots="false"
            :items="1"
            :loop="true"
            class="full-screen-slider"
        >
            <div class="owl-slide d-flex align-items-center cover" style="background-image: url('/images/Emily_Ryan.jpg');">
                <div class="container">
                    <h2>Emily &amp; Ryan</h2>
                </div>
            </div>
            <div class="owl-slide d-flex align-items-center cover" style="background-image: url('/images/Emily.jpg');">
                <div class="container">
                    <h2>Emily</h2>
                </div>
            </div>
            <div class="owl-slide d-flex align-items-center cover" style="background-image: url('/images/Ryan.jpg');">
                <div class="container">
                    <h2 class="text-center">Ryan</h2>
                </div>
            </div>
        </carousel>

        <router-link :to="{ name:'login'}" class="btn btn-login">login</router-link>
    
    </section>
</template>
<script>
    import carousel from 'vue-owl-carousel'

    export default {
        name: 'date-slider',
        components: { carousel }
    }

</script>