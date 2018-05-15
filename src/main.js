import Vue from "vue";
import VueRouter from "vue-router";
import App from "./App.vue";
import Courses from "./components/component-courses.vue";
import Sections from "./components/component-sections.vue";
import TAs from "./components/component-tas.vue";

// Enabling routing
Vue.use(VueRouter);

const routes = [
    { path: "/", component: Courses },
    { path: "/course/:course/ta/:ta", name: "section", component: Sections },
    { path: "/course/:course", name: "TAs", component: TAs },
    { path: "/*", component: Courses }
];

const router = new VueRouter({
    routes: routes
});

new Vue({
    el: "#app",
    router: router,
    render: h => h(App)
});
