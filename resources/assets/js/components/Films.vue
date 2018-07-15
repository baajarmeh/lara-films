<style scoped>

</style>

<template>
    <div>
        <div>
            <div class="row">
                <div class="col-md-12">

                    <div class="row">
                        <div class="col-md-3 col-sm-12">
                            <div class="input-group">
                                <input v-model="query" type="text" class="form-control" placeholder="Search">
                                <div class="input-group-btn">
                                    <button class="btn btn-dark" type="submit"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>

                        <div class="filter-options col-md-8 col-sm-12">
                            <div class="form-inline">

                                <label class="col-md-1 control-label inline-label" for="title">Sort: </label>
                                <select id="sorting" class="form-control" v-model.number="sorting_selected">
                                    <option v-for="sorting_option in sorting_options" v-bind:value="sorting_option.value" type="number">
                                        {{ sorting_option.text }}
                                    </option>
                                </select>

                                <select id="ordering" class="form-control" v-model.number="sort_order">
                                    <option v-for="sorting_direction in sorting_directions" v-bind:value="sorting_direction.value" type="number">
                                        {{ sorting_direction.text }}
                                    </option>
                                </select>

                                <select id="" class="form-control" v-model.number="rating_selected">
                                    <option v-for="rating_option in rating_options" v-bind:value="rating_option.value" type="number">
                                        {{ rating_option.text }}
                                    </option>
                                </select>
                            </div>
                        </div><!-- .filter-options -->

                        <div class="col-md-1 col-sm-12">
                            <button class="btn btn-success pull-right" @click="showModal = true">Add Film</button>
                        </div>

                    </div>
                </div>
            </div>
            <div class="row">
                <div id="movie-collection">
                    <div v-for="(film, index) in computedList" v-if="films.length > 0">
                        <film-item v-bind:film="film" v-bind:bus="bus" v-on:removeFilm="films.splice(index, 1)"></film-item>
                    </div>
                    <div v-else>
                        Log in to see your film collection.
                    </div>
                </div>
            </div>
        </div>

        <transition name="modal" v-if="showModal" @close="showModal = false">
            <div class="modal-mask">
                <div class="modal-wrapper">
                    <div class="modal-container">
                        <div class="card">
                            <div class="card-new">
                                <button type="button" class="close light" data-dismiss="modal" aria-label="Close" @click="showModal = false"><span aria-hidden="true">×</span></button>

                                <form class="form-horizontal">
                                    <fieldset>

                                        <legend>Add</legend>

                                        <div class="form-group">
                                            <label class="col-md-3 control-label" for="name">Name</label>
                                            <div class="col-md-7">
                                                <input id="name" name="name" type="text" placeholder="Film Name" class="form-control input-md" v-model="newFilm.name">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-3 control-label" for="description">Description</label>
                                            <div class="col-md-4">
                                                <textarea id="description" name="description" class="form-control" v-model="newFilm.description"></textarea>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-3 control-label" for="release_year">Release Year</label>
                                            <div class="col-md-3">
                                                <input id="release_year" name="release_year" type="text" class="form-control input-md" v-model.number="newFilm.release_year">
                                            </div>
                                            <label class="col-md-1 control-label" for="ticket_price">Ticket Price</label>
                                            <div class="col-md-3">
                                                <input id="ticket_price" name="ticket_price" type="text" class="form-control input-md" v-model.number="newFilm.ticket_price">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-3 control-label" for="release_year">Country</label>
                                            <div class="col-md-3">
                                                <input id="country" name="country" type="text" class="form-control input-md" v-model="newFilm.country">
                                            </div>
                                            <label class="col-md-1 control-label" for="ticket_price">Genre</label>
                                            <div class="col-md-3">
                                                <input id="genre" name="genre" type="text" class="form-control input-md" v-model.number="newFilm.genre">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-3 control-label">Rating</label>
                                            <div class="col-md-8">
                                                <star-rating :value="newFilm.rating" :bus="bus" v-model.number="newFilm.rating"class="pull-left"></star-rating>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-md-7 col-md-offset-3">
                                                <button class="btn btn-success" v-on:click.prevent="addFilm">Save</button>
                                                <button class="btn btn-danger" v-on:click.prevent @click="showModal = false">Cancel</button>
                                            </div>
                                        </div>

                                    </fieldset>
                                </form>
                            </div><!-- card reveal -->
                        </div>
                    </div>
                </div>
            </div>
        </transition>
    </div>

</template>

<script>
    // http://stackoverflow.com/a/8175221
    function sortByKey(array, key) {
        return array.sort(function(a, b) {
            var x = a[key];
            var y = b[key];
            return ((x < y) ? -1 : ((x > y) ? 1 : 0));
        });
    }

    var bus = new Vue();

    export default {
        /*
         * The component's data.
         */
        data() {
            return {
                bus: bus,
                showModal: false,
                query: '',
                rating_selected: 0,
                rating_options: [{
                    text: 'All',
                    value: false
                }, {
                    text: '1',
                    value: 1
                }, {
                    text: '2',
                    value: 2
                }, {
                    text: '3',
                    value: 3
                }, {
                    text: '4',
                    value: 4
                }, {
                    text: '5',
                    value: 5
                }, ],
                sorting_selected: 'name',
                sorting_options: [{
                    text: 'Alphabetical',
                    value: 'name'
                }, {
                    text: 'Rating',
                    value: 'rating'
                }, {
                    text: 'Release Year',
                    value: 'release_year'
                }, ],
                sorting_directions: [{
                    text: 'ASC',
                    value: 'ASC'
                }, {
                    text: 'DESC',
                    value: 'DESC'
                }, ],
                sort_order: 'ASC',
                films: [],
                show: false,
                newFilm: {},
                token: GLOBAL.token,
            };
        },
        /**
         * Prepare the component (Vue 2.x).
         */
        mounted() {
            var vm = this;
            vm.prepareComponent();
            vm.bus.$on('cancelAdd', function(movie) {
                vm.newFilm = {};
                vm.showModal = false;
                return;
            })
            vm.bus.$on('filmRated', function(data) {
                vm.newFilm['rating'] = data['rating'];
                return;
            })
        },
        computed: {
            computedList: function() {
                var vm = this;

                var data = this.films.filter(function(film) {
                    return film.name.toLowerCase().indexOf(vm.query.toLowerCase()) !== -1
                })

                if (vm.rating_selected) {
                    data = data.filter(function(film) {
                        return film.rating == vm.rating_selected;
                    });
                }

                data = sortByKey(data, vm.sorting_selected)

                if (vm.sort_order == 'DESC') {
                    data.reverse()
                }
                return data;
            }
        },

        methods: {
            /**
             * Prepare the component (Vue 2.x).
             */
            prepareComponent() {
                this.getFilms();
            },
            /**
             * Get all of the authorized tokens for the user.
             */
            getFilms() {
              var vm = this;
                axios.get('/api/films')
                    .then(response => {
                        console.log('response film data, ', response.data.data);
                        vm.films = response.data.data;
                    });
            },
            addFilm() {
                var vm = this;

                var film = {
                    'name': vm.newFilm.name,
                    'description': vm.newFilm.description,
                    'release_year': vm.newFilm.release_year,
                    'ticket_price': vm.newFilm.ticket_price,
                    'rating': vm.newFilm.rating,
                    'country': vm.newFilm.country,
                    'genre': vm.newFilm.genre,
                    'photo': '',
                }

                axios.post('/token/films?api_token=' + vm.token, film)
                    .then(response => {
                        // check response and handle error

                        vm.newFilm['photo'] = 'http://lorempixel.com/555/312/people/' + (
                            Math.ceil(Math.random() * 10) % 10);

                        this.films.push(vm.newFilm);

                        vm.newFilm = {};
                        vm.showModal = false;
                    })
            }
        },
    }
</script>
