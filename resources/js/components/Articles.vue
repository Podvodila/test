<template>
    <div class="articles-page">
        <div class="filters">
            <div class="category-filter">
                <select v-model="filters.category" class="category-selector">
                    <option value="">--Select Category--</option>
                    <option v-for="(category, key) in categories"
                            :key="key"
                            :value="category">
                        {{category}}
                    </option>
                </select>
            </div>
            <div class="search">
                <input v-model="filters.search" type="text" placeholder="Type...">
            </div>
        </div>
        <div class="list-wrap">
            <ul class="list">
                <li class="item"
                    v-for="article in articles"
                    :key="article.slug">
                    <router-link :to="{name: 'Article', params: {slug: article.slug}}">
                        {{article.title}}
                    </router-link>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                articles: [],
                filters: {
                    category: '',
                    search: '',
                },
                categories: [],
            }
        },
        methods: {
            fetchArticles() {
                axios.get('/api/articles', {
                    params: {
                        ...this.filters,
                    }
                }).then(response => {
                    this.articles = response.data;
                });
            },
            fetchCategories() {
                axios.get('/api/articles/categories').then(response => {
                    this.categories = response.data;
                });
            },
        },
        watch: {
            filters: {
                handler: function () {
                    this.fetchArticles();
                },
                deep: true,
            }
        },
        mounted() {
            this.fetchArticles();
            this.fetchCategories();
        }
    }
</script>

<style scoped lang="scss">
    .articles-page {
        max-width: 1200px;
        margin: 20px auto;

        .filters {
            display: flex;
            justify-content: space-between;

            .category-selector {
                min-width: 200px;
            }
        }
    }
</style>
