<template>
    <div>
        <navigation></navigation>

        <section class="bg-primary page-title">
                <h1>Add Photo</h1>
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
                    <!-- <input type="file" @change="onFileSelected"> -->
                    <input type="file" multiple="multiple" id="images" @change="uploadFieldChange">
                    <hr>
                    <div class="col-md-12">
                        <div class="attachment-holder animated fadeIn" v-cloak v-for="(image, index) in images"> 
                            <span class="label label-primary">{{ image.name + ' (' + Number((image.size / 1024 / 1024).toFixed(1)) + 'MB)'}}</span> 
                            <span class="" style="background: red; cursor: pointer;" @click="removeImage(image)"><button class="btn btn-xs btn-danger">Remove</button></span>
                        </div>
                    </div>

                    <span class="badge badge-danger" v-text="form.errors.get('image')" v-if="form.errors.has('image')"></span>
                </div>

                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </section>
    </div>
</template>

<script>
    import Form from '../../../classes/Form.js'
    // Uploading Image
    import FormData from 'form-data'

    export default {
        data() {
            return {
                form: new Form('/comment', 'post', true, {
                    title: '',
                    subTitle: '',
                    dayId: this.$route.params.day
                }),
                selectedFile: null,
                images: [],
                imageUploadCount: 0
            }
        },
        methods: {
            onSubmit() {
                let app = this
                let token = localStorage.getItem('token')

                // Submit Comment
                this.form.submit()
                    .then(data => {
                        console.log('Comment added successfully')

                        let images = app.images

                        // Upload Images
                        for (var i = 0; i < images.length; i++) {
                            let imageData = new FormData()
                            imageData.append('image', images[i])
                            imageData.append('commentId', data.id)

                            axios.post('/api/comment-images/', imageData, {
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
                            })
                    
                        }

                    })
                    .catch(errors => console.log(errors))
            },
            // This function will be called every time you add a file
            uploadFieldChange(e) {
                var files = e.target.files || e.dataTransfer.files;
                if (!files.length)
                    return;
                for (var i = files.length - 1; i >= 0; i--) {
                    this.images.push(files[i]);
                }
                // Reset the form to avoid copying these files multiple times into this.attachments
                document.getElementById("images").value = [];
            },
            removeImage(image) {
                this.images.splice(this.images.indexOf(image), 1);
                
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
                    this.$router.push({name: 'holiday.view', params: { 'editMode': true, 'dayStartPosition' : this.form.dayId } })
                }
                else {
                    console.log('Not all images uploaded')
                }
                
            }
        }
    }
</script>
