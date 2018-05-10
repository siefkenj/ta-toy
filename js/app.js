// 0. If using a module system (e.g. via vue-cli), import Vue and VueRouter
// and then call `Vue.use(VueRouter)`.

// 1. Define route components.
// These can be imported from other files

// 2. Define some routes
// Each route should map to a component. The "component" can
// either be an actual component constructor created via
// `Vue.extend()`, or just a component options object.
// We'll talk about nested routes later.
const routes = [
    { path: "/", component: Courses },
    { path: "/course/:course/ta/:ta", name: "section", component: Sections },
    { path: "/course/:course", name: "TAs", component: TAs },
    { path: "/*", component: Courses}
];

// 3. Create the router instance and pass the `routes` option
// You can pass in additional options here, but let's
// keep it simple for now.
const router = new VueRouter({
    routes: routes
});

var app = new Vue({
    router: router,
    el: "#app"
}).$mount("#app");
