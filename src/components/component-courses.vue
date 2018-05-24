<template>

<div id="app-course">
    <p v-if=loading>Loading...</p>
    <p v-if=err>{{ err }}</p>
    <p v-if=warn>"Data not available"</p>
    <p>Courses</p>
    <ul id="app-course">
        <li v-for="element in list">
            <router-link :to="{ name:'TAs', params:{course: element}}">
                {{ element }}</router-link>
        </li>
    </ul>
</div>

</template>

<script>

export default {
    name: "Courses",
    data: function() {
        return {
            list: null,
            err: null,
            warn: false,
            loading: true
        };
    },

    /* Before this view is rendered, fetch the data from the php file */
    created: function() {
        /* Fetch the returned json object value. */
        fetch("./get_info.php")
            .then(response => {
                return response.json();
            })
            .then(data => {
                // Turn off the loading message when the data is ready
                this.loading = false;
                // If the data type is courses as expected, set the list attribute
                // in this component
                if (data.TYPE == "courses") {
                    this.list = data.DATA;
                }
                // If it is of other types, tell it to the user
                else {
                    this.warn = true;
                }
            })
            .catch(error => {
                this.loading = false;
                this.error = true;
                return Promise.reject();
            });
    }
};

</script>
