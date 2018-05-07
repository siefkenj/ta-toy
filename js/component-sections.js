// export as global since we're not using a module system
window.Sections = Vue.component('Sections',{
  data(){
      return {
        loading: null,
        section_data: null,
        error: null
    }
  },
  beforeRouteEnter (to, from, next) {
    console.log(to.params);
    let url = "../get_info.php?course="+to.params.course+"&ta="+to.params.ta
    console.log(url);
    fetch(url).then((res) => res.json())
                          .then((data) =>{console.log(data);
                                next(
                                  (vm) => vm.section_data = data
                                )})
                          .catch((err) => next((vm) => vm.error = err.toString()));

  },
  template: '<div class="sections">'+
              '{{$route.params.course}} -> {{$route.params.ta}} -> {{section_data.TYPE}}' +
              '<div class="loading" v-if="loading">'+
                'Loading...'+
              '</div>'+

              '<div v-if="error" class="error">'+
              '  {{ error }}'+
              '</div>'+

              '<div v-if="section_data">'+
                '<div v-for="item in section_data.DATA" class="section_data">'+
                  '<a>{{ item }}</a>'+
                '</div>'+
              '</div>'+
          '</div>'
})
