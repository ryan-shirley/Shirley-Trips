<template>
    <div>
        <navigation></navigation>

        <section class="bg-primary page-title">
                <h1>Add Message</h1>
        </section>

        <section class="container">
            <form @submit.prevent="onSubmit" @keydown="form.errors.clear($event.target.name)">

                <div class="form-group">
                    <label for="title">Title <span class="required">*</span></label>

                    <input type="text" name="title" class="form-control" v-model="form.title" />

                    <span class="badge badge-danger" v-text="form.errors.get('title')" v-if="form.errors.has('title')"></span>
                </div>
                <div class="form-group">
                    <label for="subTitle">Sub Title</label>

                    <input type="text" name="title" class="form-control" v-model="form.subTitle" />

                    <span class="badge badge-danger" v-text="form.errors.get('subTitle')" v-if="form.errors.has('subTitle')"></span>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>

                    <textarea type="text" name="description" class="form-control" v-model="form.description"></textarea>

                    <span class="badge badge-danger" v-text="form.errors.get('description')" v-if="form.errors.has('description')"></span>
                </div>
                <div class="form-group">
                    <label for="image">Images</label>
                    <br />
                    <input type="file" multiple="multiple" id="images" @change="uploadFieldChange">

                    <div class="col-md-12">
                        <draggable 
                            v-model="images"
                            @change="onImageOrder"
                            v-bind="dragOptions"
                        >
                                <div class="attachment-holder animated fadeIn" v-cloak v-for="(image, index) in images" :key="image.path"> 
                                    <img :src="image_previews[index]" class="img-thumbnail" style="max-width:200px" />
                                    <span class="label label-primary">{{ image.name + ' (' + Number((image.size / 1024 / 1024).toFixed(1)) + 'MB)'}}</span> 
                                    <span class="" style="background: red; cursor: pointer;" @click.prevent="removeImage(image)"><button class="btn btn-xs btn-danger">Remove</button></span>
                                </div>
                        </draggable>
                    </div>

                    <span class="badge badge-danger" v-text="form.errors.get('image')" v-if="form.errors.has('image')"></span>
                </div>

                <button v-if="!loading" type="submit" class="btn btn-primary">Submit</button>

                <button v-if="loading" class="btn btn-primary" type="button" disabled>
                    <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                    Submitting...
                </button>
            </form>
        </section>
    </div>
</template>

<script>
    import Form from '../../../classes/Form.js'
    // Uploading Image
    import FormData from 'form-data'
    // Reorder Images
    import draggable from 'vuedraggable'

    export default {
        data() {
            return {
                form: new Form('/comment', 'post', true, {
                    title: '',
                    subTitle: '',
                    description: '',
                    dayId: this.$route.params.dayId
                }),
                selectedFile: null,
                images: [],
                image_previews: [],
                imageUploadCount: 0,
                loading: false
            }
        },
        methods: {
            onSubmit() {
                let app = this
                let token = localStorage.getItem('token')
                app.loading = true

                // Submit Comment
                this.form.submit()
                    .then(data => {
                        console.log('Comment added successfully')

                        if(app.images.length > 0) {
                            let images = app.images

                            // Upload Images
                            for (var i = 0; i < images.length; i++) {
                                let imageData = new FormData()
                                imageData.append('image', images[i])
                                imageData.append('order', (i + 1) )
                                imageData.append('commentId', data.id)

                                axios.post('/api/comment-images', imageData, {
                                    headers: { 
                                        Authorization: "Bearer " + token,
                                        'Content-Type': 'multipart/form-data'
                                    }
                                })
                                .then(function (resp) {
                                    console.log('Successfully uploaded.')
                                    app.imageUploadCount = app.imageUploadCount + 1

                                    app.CheckImageUploadCompletion()
                                })
                                .catch(function (resp) {
                                    alert('Could not save image')
                                    console.log(resp)
                                    app.loading = false
                                })
                            }
                    
                        }
                        else {
                            app.$router.push({name: 'holiday.view.day', params: { 'dayId': this.$route.params.dayId } })
                        }

                    })
                    .catch(errors => {
                        console.log(errors)
                        app.loading = false
                    })
            },
            // This function will be called every time you add a file
            uploadFieldChange(e) {
                var files = e.target.files || e.dataTransfer.files;
                if (!files.length)
                    return;
                for (var i = files.length - 1; i >= 0; i--) {
                    this.images.push(files[i]);

                    // Generate image preview
                    var reader = new FileReader();
                    // Define a callback function to run, when FileReader finishes its job
                    reader.onload = (e) => {
                        this.image_previews.push(e.target.result)
                    }
                    // Start the reader job - read file as a data url (base64 format)
                    reader.readAsDataURL(files[i]);
                }
                // Reset the form to avoid copying these files multiple times into this.attachments
                document.getElementById("images").value = []
            },
            removeImage(image) {
                this.images.splice(this.images.indexOf(image), 1);
                this.image_previews.splice(this.images.indexOf(image), 1);
                
                this.getAttachmentSize();
            },
            getAttachmentSize() {
                this.upload_size = 0; // Reset to beginning
                this.images.map((item) => { this.upload_size += parseInt(item.size); });
                
                this.upload_size = Number((this.upload_size).toFixed(1));
                this.$forceUpdate();
            },
            CheckImageUploadCompletion() {
                if(this.imageUploadCount == this.images.length) {
                    console.log('All images uploaded')
                    this.$router.push({name: 'holiday.view.day', params: { 'dayId': this.$route.params.dayId } })
                }
                else {
                    console.log('Not all images uploaded')
                }
                
            },
            onImageOrder(event) {
                let new_index = event.moved.newIndex
                let old_index = event.moved.oldIndex
                let arr = this.image_previews

                while (old_index < 0) {
                    old_index += arr.length;
                }
                while (new_index < 0) {
                    new_index += arr.length;
                }
                if (new_index >= arr.length) {
                    var k = new_index - arr.length;
                    while ((k--) + 1) {
                        arr.push(undefined);
                    }
                }
                arr.splice(new_index, 0, arr.splice(old_index, 1)[0]);  

                this.getAttachmentSize();
            }
        },
        components: {
            draggable,
        },
        computed: {
            dragOptions() {
                return {
                    animation: 600,
                    group: "description",
                    disabled: false,
                    ghostClass: "ghost"
                };
            }
        }
    }
</script>
<style scoped>
    .ghost {
        opacity: 0.5;
    }
</style>