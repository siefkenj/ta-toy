// export as global since we're not using a module system
window.Courses = Vue.component("Courses", {
    name: "Courses",
    data: function() {
        return {
            list: null,
            err: null,
            warn: false
        };
    },

    /* Before this view is rendered, fetch the data from the php file */
    mounted () {
        /* Fetch the returned json object value. */
        fetch("get_info.php")
        .then(function (response) {
            return response.json()
        })
        .then(data => {
            if (Array.isArray(data.DATA)) {
                console.log(data.DATA);
                this.list = data.DATA;
            } else {
                console.log("data.DATA is not an appropriate array!");
                this.warn = true;
            }
        })
        .catch(error => {
            this.err = error.toString();
            console.log("Here is error in fetching data!");
        });
    },
    template: `<div id="app-course">
        <p v-if=err>{{ err }}</p>
        <p v-if=warn>"data.DATA is not an appropriate array!"</p>
        <ul id="app-course">
        <li v-for="element in list">
        <router-link :to="{ name:\'TAs\', params:{course: element}}">
        {{ element }}</router-link></li>
        </ul></div>`
});
