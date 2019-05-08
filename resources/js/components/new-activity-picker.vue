<template>
    <div>
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary floating-activity-btn" data-toggle="modal" data-target="#newActivityModal">
        +
        </button>

        <!-- Modal -->
        <div class="modal fade" id="newActivityModal" tabindex="-1" role="dialog" aria-labelledby="newActivityModalTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="newActivityModalTitle">Select Activity Type</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <ul class="list-group">
                            <button type="button" class="list-group-item list-group-item-action" @click="newActivitySelected('flight')">Flight</button>
                            <button type="button" class="list-group-item list-group-item-action" @click="newActivitySelected('photo')">Photo</button>
                            <button type="button" class="list-group-item list-group-item-action" @click="newActivitySelected('message')">Message</button>
                            <button type="button" class="list-group-item list-group-item-action" @click="newActivitySelected('hotel')">Hotel</button>
                        </ul>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
                else if (acitvity == 'photo') {
                    this.$router.push({ name: 'holiday.add.photo', params: { 'day': this.dayId } })
                }
            }
        }
    }
  </script>