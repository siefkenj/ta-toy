// export as global since we're not using a module system
window.Sections = Vue.component('Sections',{
  data(){
      return {
        loading: true,
        section_data: null,
        error: null
    }
  },

  beforeRouteEnter (to, from, next) {
    let url = "../get_info.php?course="+to.params.course+"&ta="+to.params.ta
    fetch(url)
    .then((res) => res.json())
                          .then((data) =>{console.log(data);
                                next(
                                  (vm) => {vm.section_data = data;vm.loading = false}
                                )})
                          .catch((err) => next((vm) => vm.error = err.toString()));

  },

  template: '<div class="sections">'+
              '<div class="loading" v-if="loading">'+
                'Loading...'+
              '</div>'+
              '<div v-else>' +
                '<router-link to="/course">{{$route.params.course}}</router-link> -> <router-link :to="{ name: \'TAs\', params: { course: $route.params.course }}">{{$route.params.ta}}</router-link> -> {{section_data.TYPE}}' +

                '<div v-if="error" class="error">'+
                '  {{ error }}'+
                '</div>'+

                '<div v-if="section_data">'+
                  '<div v-for="item in section_data.DATA" class="section_data">'+
                    '<a>{{ item }}</a>'+
                  '</div>'+
                '</div>'+
              '</div>'+
            '</div>'
})
