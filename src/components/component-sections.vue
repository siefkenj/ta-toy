<template>

<div id="app">
    <div class="sections">
        <div class="loading" v-if="loading">
            Loading...
        </div>
        <div v-else>
            <router-link to="/">{{$route.params.course}}</router-link> ->
            <router-link :to="{ name: 'TAs', params: { course: $route.params.course }}">TAs in {{$route.params.course}}</router-link> -> Sections for {{$route.params.ta}}
            <div v-if="error" class="error">{{ error }}</div>
            <div v-else-if="section_data">
                <div v-for="item in section_data.DATA" class="section_data">
                    <a>{{ item }}</a>
                </div>
            </div>
            <div v-else>
                No section is available for {{$route.params.ta}}
            </div>
        </div>
    </div>
</div>

</template>

<script>

export default {
    data: function() {
        return {
            loading: true,
            section_data: null,
            error: null
        };
    },
    //fetch data when component is mounted
    created: function() {
        let url =
            "get_info.php?course=" +
            this.$route.params.course +
            "&ta=" +
            this.$route.params.ta;
        fetch(url)
            .then(res => res.json())
            .then(data => {
                this.section_data = data;
                this.loading = false;
            })
            .catch(err => {
                this.error = true;
                this.loading = false;
            });
    }
};

</script>
