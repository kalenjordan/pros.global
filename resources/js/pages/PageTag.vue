<template>
    <div>
        <top-nav></top-nav>
        <div class="page page-tag">
            <div class="text-center max-w-lg mx-auto mb-4 mt-2">
                <h1>
                    {{ tag.headline ? tag.headline : tag.name }}
                </h1>
            </div>
            <div class="section mx-auto max-w-md">
                <div class="card">
                    <div class="card--inner text-left">
                        Need to insert users here
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                tag: {}
            }
        },
        mounted() {
            let self = this;
            axios.get('/api/v1/tags/' + this.$route.params.slug).then(function(response) {
                self.tag = response.data;
            });
        }
    }
</script>
