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
    mounted: function () {
        /* Fetch the returned json object value. */
        fetch("get_info.php")
        .then(function (response) {
            return response.json()
        })
        .then(data => {
            // If the data type is courses as expected, set the list attribute
            // in this component
            if (data.TYPE == "courses") {
                console.log(data.DATA);
                this.list = data.DATA;
            }
            // If the data type is error, display the error message
            else if (data.TYPE == "error") {
                console.log("data.DATA is not an appropriate array!");
                this.err = "The error data is getting back from the php file!";
            }
            // If it is of other types, tell it to the user
            else {
                this.warn = true;
            }
        })
        .catch(error => {
            this.err = error.toString();
            console.log("Here is error in fetching data!");
        });
    },
    template:
        `<div id="app-course">
            <p v-if=err>{{ err }}</p>
            <p v-if=warn>"You are not getting back the course list!"</p>
            <ul id="app-course">
                <li v-for="element in list">
                    <router-link :to="{ name:\'TAs\', params:{course: element}}">
                    {{ element }}</router-link>
                </li>
            </ul>
        </div>`
});
