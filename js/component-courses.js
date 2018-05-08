
// let courses = '<ul id="app-course">' +
//         '<li v-for="course in list">{{ course }}</li>' +
//         '</ul>';
// let list;

// /* Fetch the returned json object value. */
// fetch('get_info.php').then(function (response) {
//     list = JSON.parse(response);
// });

// export as global since we're not using a module system
window.Courses = Vue.component('Courses', {
    name: "Courses",
    data: function () {
        return {list:null};
    },
    beforeRouteEnter (to, from, next) {
        let list;
        console.log("123");
        /* Fetch the returned json object value. */
        fetch('get_info.php').then(function (response) {
            self.list = response.json().then(function (data) {
                self.list = data.DATA;
                next(
                    function (vm) {
                        vm.list = self.list;
                    }
                )
                console.log(data.DATA);
            });
        });
    },
    template: '<div id="app-course">' +
        '<ul id="app-course">' +
        '<li v-for="element in list">' +
        '<router-link :to="{ name:\'TAs\', params:{course: element}}">' +
        '{{ element }}</router-link></li>' +
        '</ul></div>'
});
