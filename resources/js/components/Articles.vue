<template>
    <div>
        <div class="filters">
            <!--TODO-->
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
            }
        },
        methods: {
            fetchArticles() {
                axios.get('/api/articles').then(response => {
                    this.articles = response.data;
                });
            },
        },
        mounted() {
            this.fetchArticles();
        }
    }
</script>

<style scoped lang="scss">

</style>
