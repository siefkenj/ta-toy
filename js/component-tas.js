// export as global since we're not using a module system
window.TAs = Vue.component("TAs", {
    // Data to store TA names
    data: function() {
        return {
            loading:true,
            TAs: null,
            error:null
        };
    },

    created() {
        fetch("get_info.php?course=" + this.$route.params.course)
            .then(response => {
                return response.json();
            })
            .then(data => {
                this.TAs = data.DATA;
                this.loading = false;
            })
            .catch(err => {
                this.error = err.toString();
                this.loading = false;
            });
    },

    template: `
        <div>
            <router-link to="/">{{$route.params.course}}</router-link> -> TAs in {{$route.params.course}}
            <div v-if="loading">
                <h3>Loading...</h3>
            </div>
            <div v-else>
                <div v-if="error">
                    {{error}}
                </div>
                <div v-else-if="TAs">
                    <ul v-for="TA in TAs">
                        <router-link :to=\"{ name: 'section', params: {ta: TA}}\">{{TA}}</router-link>
                    </ul>
                </div>
                <div v-else>
                    <h3>No TAs found for {{$route.params.course}}</h3>
                </div>
            </div>
        </div>`
});
