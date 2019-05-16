<template>
    <router-link :to="{ name:'holiday.view.message', params: { 'commentId' :comment.id }}" class="card-wrapper" v-if="!reOrderMode">
        <div class="card comment" v-if="comment.images.length == 1">
            <img :src="comment.images[0].path" class="card-img-top" :alt="comment.title">
            <div class="card-body">
                <h5 class="card-title">{{ comment.title }}</h5>
                <p class="card-text">{{ comment.subTitle }}</p>
            </div>
        </div>
        <div class="card comment double" v-else-if="comment.images.length == 2">
            <div class="row no-gutters">
                <div v-for="image in orderedImages" class="col-6" :key="'activity_image' + image.id">
                    <img :src="image.path" class="card-img" />
                </div>
            </div>
            <div class="card-body">
                <h5 class="card-title">{{ comment.title }}</h5>
                <p class="card-text">{{ comment.subTitle }}</p>
            </div>
        </div>
        <div class="card comment tripple" v-else>
            <div class="row no-gutters" v-if="comment.images.length > 0">
                <div class="col-8 left">
                    <img :src="orderedImages[0].path" class="card-img" />
                </div>
                <div class="col-4 right">
                    <img :src="orderedImages[1].path" class="card-img" />
                    <img :src="orderedImages[2].path" class="card-img" />
                </div>
            </div>
            <div class="card-body">
                <h4>{{ comment.title }}</h4>
                <p class="card-text">{{ comment.subTitle }}</p>
            </div>
        </div>
        <!--/.Comment -->
    </router-link>
    <div class="activity-card" v-else>
        <div class="body">
            <h4>{{ comment.title }}</h4>
            <p>{{ comment.subTitle }}</p>
        </div>
        <img v-if="comment.images.length > 0" :src="comment.images[0].path" :alt="comment.title">
    </div>
</template>
<script>
    export default {
        name: 'comment-details',
        props: {
            comment: Object,
            reOrderMode: Boolean,
            dayId: Number
        },
        computed: {
            orderedImages: function () {
                return _.orderBy(this.comment.images, 'order')
            }
        }
    }
</script>
