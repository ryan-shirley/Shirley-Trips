<template>
    <div>
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary floating-activity-btn" data-toggle="modal" data-target="#newActivityModal">
        +
        </button>

        <!-- Modal -->
        <div class="modal fade new-activity" id="newActivityModal" tabindex="-1" role="dialog" aria-labelledby="newActivityModalTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="newActivityModalTitle">Select Activity Type</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="card" @click="newActivitySelected('flight')">
                                    <div class="card-body">
                                        <i class="fas fa-plane flight"></i>
                                        <h4>Flight</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="card" @click="newActivitySelected('message')">
                                    <div class="card-body">
                                        <i class="fas fa-camera message"></i>
                                        <h4>Message</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="card" @click="newActivitySelected('hotel')">
                                    <div class="card-body">
                                        <i class="fas fa-hotel hotel"></i>
                                        <h4>Hotel</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="card" @click="newActivitySelected('video')">
                                    <div class="card-body">
                                        <i class="fas fa-video video"></i>
                                        <h4>Video</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        name: 'new-acitvity-picker',
        props: {
            dayId: {
                type: [String, Number]
            }
        },
        methods: {
            newActivitySelected(acitvity) {
                $('#newActivityModal').modal('hide');

                if(acitvity == 'message') {
                    this.$router.push({ name: 'holiday.add.message', params: { 'day': this.dayId } })
                }
                else if (acitvity == 'flight') {
                    this.$router.push({ name: 'holiday.add.flight', params: { 'day': this.dayId } })
                }
                else if (acitvity == 'hotel') {
                    this.$router.push({ name: 'holiday.add.hotel', params: { 'day': this.dayId } })
                }
                else if (acitvity == 'video') {
                    this.$router.push({ name: 'holiday.add.video', params: { 'day': this.dayId } })
                }
            }
        }
    }
  </script>