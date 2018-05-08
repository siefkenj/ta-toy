// export as global since we're not using a module system
window.TAs = Vue.component("TAs", {
    // Data to store TA names
    data: function() {
        return {
            TAs: null
        };
    },

    mounted() {
        fetch("../get_info.php?course=" + this.$route.params.course)
            .then(response => {
                return response.json();
            })
            .then(data => {
                this.TAs = data.DATA;
            })
            .catch(error => {
                console.log(error);
            });
    },

    template: `
        <div v-bind:style=\"{ marginLeft: 100+ 'px', paddingTop: 25+ 'px'}\">
        <router-link to="/course">Back to Courses</router-link>
        <ul v-for="TA in TAs">
        <router-link :to=\"{ name: 'section', params: {ta: TA}}\">{{TA}}</router-link>
        </ul>
        </div>`
});
