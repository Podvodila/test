<template>
    <div v-if="article" class="article-page">
        <h2 class="title" v-html="article.title"></h2>
        <div class="category">
            <strong>{{article.mainCategory}}</strong>
        </div>
        <img :src="imageUrl" alt="Article image" class="article-image">
        <p v-html="article.content"></p>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                article: null,
                slug: this.$router.currentRoute.params.slug,
            }
        },
        methods: {
            fetchArticle() {
                axios.get('/api/articles/show/' + this.slug).then(response => {
                    this.article = response.data;
                });
            },
        },
        computed: {
            imageUrl() {
                return this.article.image
                    ? this.article.image
                    : '/images/placeholder-img.jpg';
            },
        },
        mounted() {
            this.fetchArticle();
        }
    }
</script>

<style scoped lang="scss">
    .article-page {
        .article-image {
            max-height: 200px;
        }
    }
</style>
