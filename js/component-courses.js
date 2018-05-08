// export as global since we're not using a module system
window.Courses = Vue.component("Courses", {
    name: "Courses",
    data: function() {
        return {
            list: null,
            err: null
        };
    },
    /* Before this view is rendered, fetch the data from the php file */
    beforeRouteEnter(to, from, next) {
        /* Fetch the returned json object value. */
        fetch("get_info.php")
            .then(function(response) {
                response.json().then(function(data) {
                    next(function(vm) {
                        vm.list = data.DATA;
                    });
                });
                /* Handle errors in fetching the data */
            })
            .catch(function(error) {
                next(function(vm) {
                    vm.err = error.toString();
                });
                console.log("Here is error in fetching data!");
            });
    },
    template: `<div id="app-course">
        <p v-if=err>{{ err }}</p>
        <ul id="app-course">
        <li v-for="element in list">
        <router-link :to="{ name:\'TAs\', params:{course: element}}">
        {{ element }}</router-link></li>
        </ul></div>`
});
