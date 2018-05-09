// export as global since we're not using a module system
window.Sections = Vue.component("Sections", {
    data: function() {
        return {
            loading: true,
            section_data: null,
            error: null
        };
    },
    //fetch data when component is created
    created: function() {
        let url =
            "../database.php?course=" +
            this.$route.params.course +
            "&ta=" +
            this.$route.params.ta;
        fetch(url)
            .then(res => {
                section_data = error = null;
                loading: true;
                return res.json();
            })
            .then(result => {
                this.loading = false;
                if (result.status == "EMPTY") {
                    this.error = "No Sections Found";
                } else {
                    this.section_data = result;
                }
            })
            .catch(err => (this.error = err.toString()));
    },
    template: `
        <div class="sections">
            <div class="loading" v-if="loading">
                Loading...
            </div>
            <div v-else>
                <router-link to="/course">{{$route.params.course}}</router-link> -> <router-link :to="{ name: \'TAs\', params: { course: $route.params.course }}">TAs</router-link> -> {{$route.params.ta}}
                <div v-if="error" class="error">
                    {{ error }}
                </div>
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
        `
});
