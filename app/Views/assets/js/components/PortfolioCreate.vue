<template>
    <div class="actions-meta">
        <div class="row">
            <div class="col-12 col-sm-6 col-xl-12">
                <div class="card mb-4">
                    <h5 class="card-header">Publish</h5>
                    <div class="card-footer d-flex">
                        <button type="submit" class="btn btn-primary ml-auto"><i data-feather="save"></i> Save</button>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-12">
                <div class="card mb-4">
                    <h5 class="card-header clearfix">
                        Categories
                        <span class="float-right">
                            <a class="badge badge-dark" data-toggle="collapse" href="#newCategory" role="button" aria-expanded="false" aria-controls="newCategory">+ Add new category</a>
                        </span>
                    </h5>
                    <div class="card-body taxo-category" v-if="categories.length">
                        <div v-for="category in categories" v-bind:key="category.id" class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" name="portfolio.category[]" :id="category.id" :value="category.id" v-model="portfolio.category_id">
                            <label class="custom-control-label" :for="category.id">{{ category.name }}</label>
                        </div>
                    </div>
                    <div class="card-body taxo-category" v-else>
                        No category
                    </div>
                    <div class="collapse" id="newCategory">
                        <div class="card-footer">
                            <form @submit.prevent="addCategory" id="category" class="input-group">
                                <input type="text" class="form-control" placeholder="New categories" v-model="category.name" required>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-primary" type="submit">Add</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-12">
                <div class="card mb-4">
                    <h5 class="card-header">
                        Tags
                        <span class="float-right">
                            <a class="badge badge-dark" data-toggle="collapse" href="#newTag" role="button" aria-expanded="false" aria-controls="newCategory">+ Add new tag</a>
                        </span>
                    </h5>
                    <ul class="card-body list-inline mb-0" v-if="portfolio.tag.length">
                        <li class="list-inline-item mr-3" v-for="tag in portfolio.tag" v-bind:key="tag.id">
                            <button type="button" class="btn btn-danger rounded-circle remove-tag mr-1" v-on:click="portfolio.tag.pop(portfolio.tag)">&times;
                            </button>{{ tag.name }}
                            <input type="hidden" name="portfolio.tag[]" :value="tag.id">
                        </li>
                    </ul>
                    <ul class="card-body list-inline mb-0" v-else>
                        No tag
                    </ul>
                    <ul class="card-footer list-inline mb-0" v-if="tags.length">
                        <h6>Availlable Tags</h6>
                        <li class="list-inline-item mr-3" v-for="tag in tags" v-bind:key="tag.id">
                            <button type="button" class="btn btn-success rounded-circle remove-tag mr-1" @click="pushTag(tag)">&plus;</button>{{ tag.name }}
                        </li>
                    </ul>
                    <span v-else>
                    </span>
                    <div class="collapse" id="newTag">
                        <div class="card-footer">
                            <form @submit.prevent="addTag" id="tag" class="input-group">
                                <input type="text" class="form-control" placeholder="New tag" v-model="tag.name" required>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-primary" type="submit">Add</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-12">
                <div class="card mb-4">
                    <h5 class="card-header">Thumbnail</h5>
                    <div class="card-body">
                        <div v-if="portfolio.image !== null" class="text-center">
                            <img :src="portfolio.image.thumbnail" class="img-fluid mb-3">
                            <a class="d-block text-left" @click="removeThumbnail" href="#" >Remove featured image</a>
                            <input type="hidden" name="image" :value="portfolio.image.id">
                        </div>
                        <div v-else>
                            <a href="#" @click.prevent="setThumbnail" data-toggle="modal" data-target="#imagemodal">Set featured image</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { bus } from '../dashboard.js';

    export default {
        data(){
            return {
                portfolio: {
                    image: null,
                    category: [],
                    tag: [],
                    media: [],
                    category_id: []
                },
                categories: [],
                category: {
                    id: '',
                    name: '',
                    type: 'portfolio'
                },
                tags: [],
                tag: {
                    id: '',
                    name: '',
                    type: 'portfolio'
                },
                modalActive: false,
                isThumbnail: false,
                portfolioMedia: []
            }
        },

        created() {
            this.fetchPortfolio();
            this.fetchCategories();
            this.fetchTags();
            bus.$on('theImage', (data) => {
                this.portfolio.image = data
            });
            bus.$on('thumbFalse', (data) => {
                this.isThumbnail = data
            });
        },

        methods: {
            setThumbnail(){
                bus.$emit('thumbTrue', this.isThumbnail = true);
            },
            removeThumbnail(){
                this.portfolio.image = null;
            },
            fetchPortfolio(){
                let id = $('#portfolio').data('id');

                // If has id = Edit
                // no = Create
                if(id != null){
                    axios.get(this.$baseUrl + '/api/portfolio/' + id)
                        .then(response => {
                        this.portfolio = response.data.data;
                        bus.$emit('thePortfolio', this.portfolio = response.data.data);
                    })
                    .catch(error => console.log(error));
                }
                
            },
            fetchCategories(){
                axios.get(this.$baseUrl + '/api/category?type=portfolio')
                .then(response => {
                    this.categories = response.data.data;
                })
                .catch(error => console.log(error));
            },
            addCategory(){
                axios.post(this.$baseUrl + '/api/category', this.category )
                .then(data => {
                    this.category.name = '';
                    this.fetchCategories();
                })
                .catch(error => console.log(error));
            },
            fetchTags(){
                axios.get(this.$baseUrl + '/api/tag?type=portfolio')
                .then(response => {
                    this.tags = response.data.data;
                })
                .catch(error => console.log(error));
            },
            addTag(){
                axios.post(this.$baseUrl + '/api/tag', this.tag)
                .then(data => {
                    this.tag.name = '';
                    this.fetchTags();
                })
                .catch(error => console.log(error));
            },
            pushTag(tag){
                if(this.portfolio.tag.includes(tag)){
                    alert('You have already added this tag.');
                } else {
                    this.portfolio.tag.push(tag);
                }
            }
        }
    }
</script>