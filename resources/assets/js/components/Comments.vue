<style scoped>

</style>

<template>
    <div class="col-md-6" v-if="comment && visible">
        <div class="card">
            <div class="card-content">
                <span class="card-title">{{ comment.name }}</span>
                <div class="btn-group pull-right">
                    <button type="button" class="btn btn-custom dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-ellipsis-v"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a href="#" @click="edit = !edit" v-on:click.prevent>Edit <i class="fa fa-pencil pull-right fa-custom"></i></a></li>
                        <li><a href="#" v-on:click.prevent="deleteComment">Delete <i class="fa fa-trash pull-right fa-custom"></i></a></li>
                    </ul>
                </div>
                <h4 class="pull-right">{{ comment.comment }}</h4>
            </div><!-- card content -->
            <transition name="custom-slide-transition"
                        enter-active-class="animated flipInX"
                        leave-active-class="animated flipOutX">
                <div class="card-reveal" v-if="edit">
                    <span class="card-title">{{ comment.name }}</span> <button type="button" class="close light" data-dismiss="modal" aria-label="Close" @click="edit = !edit"><span aria-hidden="true">×</span></button>

                    <form class="form-horizontal">
                        <fieldset>

                            <legend>Edit</legend>

                            <div class="form-group">
                                <label class="col-md-3 control-label" for="title">Name</label>
                                <div class="col-md-7">
                                    <input id="name" name="name" type="text" placeholder="Comment Name" class="form-control input-md" v-model="comment.name">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label" for="description">Comment</label>
                                <div class="col-md-4">
                                    <textarea id="comment" name="comment" class="form-control" v-model="comment.comment"></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-7 col-md-offset-3">
                                    <button class="btn btn-success" v-on:click.prevent="updateComment">Save Changes</button>
                                    <button class="btn btn-danger" v-on:click.prevent="edit = !edit">Cancel</button>
                                </div>
                            </div>

                        </fieldset>
                    </form>
                </div><!-- card reveal -->

            </transition>
        </div>
    </div>
</template>

<script>
    export default {
        /*
         * The component's data.
         */
        data() {
            return {
                visible: true,
                dropdown: false,
                edit: false,
                token: GLOBAL.token
            };
        },
        props: [
            'comment'
        ],
        methods: {
            /**
             * Update the film.
             */
            updateComment() {
                axios.put('/token/films/' + this.comment.film_id + '/comments/' + this.comment.id + '?api_token=' + this.token, this.comment)
                    .then(response => {
                        // check response and handle error
                        this.edit = false;
                    })
            },
            /**
             * Delete the film.
             */
            deleteComment() {
                var vm = this;
                axios.delete('/token/films/' + this.comment.film_id + '/comments/' + this.comment.id + '?api_token=' + this.token)
                    .then(response => {
                        // check response and handle error
                        console.log('remove response, ', response.data);
                        // send an event to an event bus that the parent is listening on
                        this.bus.$emit('removeComment', vm.comment);
                        this.visible = false;
                    })
            }
        }
    }
</script>