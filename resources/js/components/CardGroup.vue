<template>
    <div class="card">
        <div class="card--background"></div>
        <div class="card--inner">
            <div class="card--avatar">
                <a href="#">
                    <img src="https://pbs.twimg.com/profile_images/959539398210547712/U5lQBX2N_400x400.jpg">
                </a>
            </div>
            <div class="card--cta">
                <a class="btn" href="#"><i class="far fa-thumbs-up"></i> Give Props</a>
            </div>
            <div class="card--identity mb-1">
                <div class="card--identity--name bold">
                    <a href="#" class="naked-link">{{ user }}</a>
                </div>
                <div class="card--identity--handle font-small">
                    <a href="#" class="naked-link">@kalenjordan</a>
                </div>
            </div>
            <div class="card--about font-small mb-1">
                Magento fanboy. Small-time entrepreneur. Wannabe Youtuber. Not a recruiter. Founder @commercehero. Advisor @getmagemail. Co-host @magetalk
            </div>
            <div class="card--tags font-small">
                <a class="tag" href="#"><i class="fa fa-location-arrow"></i> Austin</a>
                <a class="tag" href="#">Magento</a>
                <a class="tag" href="#">SaaS</a>
                <a class="tag" href="#">Seeking Advisees</a>
                <a class="tag" href="#">Developer</a>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                users: [],
                user: 'test'
            }
        },
        mounted() {
            let self = this;
            axios.get('/api/v1/users').then(function(response) {
                console.log('api response: ' + response.data);
                self.users = response.data;
                self.user = response.data[0];
            });
        }
    }
</script>
