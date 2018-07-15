<style scoped>

</style>

<template>
    <div class="col-md-6" v-if="film && visible">
        <div class="card">
            <div class="card-image">
                <img class="img-responsive" :src="film.photo">
            </div><!-- card image -->

            <div class="card-content">
                <span class="card-title">{{ the_film.name }}</span>
                <div class="btn-group pull-right">
                    <button type="button" class="btn btn-custom dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-ellipsis-v"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a href="#" @click="edit = !edit" v-on:click.prevent>Edit <i class="fa fa-pencil pull-right fa-custom"></i></a></li>
                        <li><a href="#" v-on:click.prevent="deleteFilm">Delete <i class="fa fa-trash pull-right fa-custom"></i></a></li>
                    </ul>
                </div>
                <h4 class="pull-right">{{ the_film.release_year }}</h4>
            </div><!-- card content -->
            <div class="card-action">
                <a href="#" @click="details = !details" v-on:click.prevent>DETAILS</a>
                <span><em>{{ the_film.genre }}</em></span>
                <span class="pull-right">
                    <span class="btn-custom"><i class="fa fa-clock-o"></i> {{ the_film.country }}</span>
                    <span class="btn-custom"><i class="fa fa-star"></i> {{ the_film.rating }}</span>
                </span>
            </div><!-- card actions -->
            <transition name="custom-slide-transition"
                        enter-active-class="animated flipInX"
                        leave-active-class="animated flipOutX">
                <div class="card-reveal" v-if="details">
                    <span class="card-title">{{ the_film.name }}</span> <button type="button" class="close light" data-dismiss="modal" aria-label="Close" @click="details = !details"><span aria-hidden="true">×</span></button>
                    <img class="img-responsive" :src="film.photo">
                    <p>{{ the_film.description }}</p>

                    <div class="row">
                        <h5>Comments</h5>
                        <div id="comment-collection">
                            <div v-for="(comment, index) in comments" v-if="comments.length > 0">
                                <comment-item v-bind:comment="comment" v-on:removeComment="comments.splice(index, 1)"></comment-item>
                            </div>
                        </div>
                    </div>
                </div><!-- card reveal -->

                <div class="card-reveal" v-if="edit">
                    <span class="card-title">{{ the_film.name }}</span> <button type="button" class="close light" data-dismiss="modal" aria-label="Close" @click="edit = !edit"><span aria-hidden="true">×</span></button>

                    <form class="form-horizontal">
                        <fieldset>

                            <legend>Edit</legend>

                            <div class="form-group">
                                <label class="col-md-3 control-label" for="title">Name</label>
                                <div class="col-md-7">
                                    <input id="name" name="name" type="text" placeholder="Film Name" class="form-control input-md" v-model="film.name">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label" for="description">Description</label>
                                <div class="col-md-4">
                                    <textarea id="description" name="description" class="form-control" v-model="film.description"></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label" for="release_year">Release Year</label>
                                <div class="col-md-3">
                                    <input id="release_year" name="release_year" type="text" class="form-control input-md" v-model.number="film.release_year">
                                </div>
                                <label class="col-md-1 control-label" for="ticket_price">Ticket Price</label>
                                <div class="col-md-3">
                                    <input id="ticket_price" name="ticket_price" type="text" class="form-control input-md" v-model.number="film.ticket_price">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label" for="release_year">Country</label>
                                <div class="col-md-3">
                                    <input id="country" name="country" type="text" class="form-control input-md" v-model="film.country">
                                </div>
                                <label class="col-md-1 control-label" for="ticket_price">Genre</label>
                                <div class="col-md-3">
                                    <input id="genre" name="genre" type="text" class="form-control input-md" v-model.number="film.genre">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label">Rating</label>
                                <div class="col-md-8">
                                    <star-rating :id="film.id" :value="film.rating" :bus="bus" class="pull-left"></star-rating>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-7 col-md-offset-3">
                                    <button class="btn btn-success" v-on:click.prevent="updateFilm">Save Changes</button>
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
                details: false,
                edit: false,
                token: GLOBAL.token,
                comments: []
            };
        },
        props: [
            'film',
            'bus'
        ],
        /**
         * Prepare the component (Vue 2.x).
         */
        mounted() {
            var vm = this;
            vm.bus.$on('filmRated', function(data) {
                if (data['id'] == vm.the_film.id) {
                    vm.the_film['rating'] = data['rating'];
                }
                return;
            });
            this.getComments();
        },
        computed: {
            the_film: function() {
                var vm = this;
                var film = vm.film;
                var image = 'http://lorempixel.com/555/312/people/' + (film.id % 10);

                axios.get('/api/films/' + vm.film.id)
                    .then(response => {
                        console.log('res data ', response.data);
                        film = response.data;
                        film['photo'] = image;
                    })
                return film;
            }
        },
        methods: {
            getComments() {
                var vm = this;
                axios.get('/api/films/' + vm.film.id + '/comments')
                    .then(response => {
                        console.log('response comments data, ', response.data)
                        vm.comments = response.data.data;
                    });
            },
            /**
             * Update the film.
             */
            updateFilm() {
                axios.put('/token/films/' + this.film.id + '?api_token=' + this.token, this.film)
                    .then(response => {
                        // check response and handle error
                        this.edit = false;
                    })
            },
            /**
             * Delete the film.
             */
            deleteFilm() {
                var vm = this;
                axios.delete('/token/films/' + this.film.id + '?api_token=' + this.token)
                    .then(response => {
                        // check response and handle error
                        console.log('remove response, ', response.data);
                        // send an event to an event bus that the parent is listening on
                        this.bus.$emit('removeFilm', vm.film);
                        this.visible = false;
                    })
            }
        }
    }
</script>
