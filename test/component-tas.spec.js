import { shallow } from '@vue/test-utils';
import { createLocalVue } from '@vue/test-utils';
import VueRouter from "vue-router";
import sections from '../src/components/component-sections.vue';
import courses from "../src/components/component-courses.vue";
import TAs from "../src/components/component-tas.vue";
import "isomorphic-fetch";
let expect = require("expect.js");


const localVue = createLocalVue();
localVue.use(VueRouter);
const $route = [
    { path: "/course/:course/ta/:ta", name: "section", component: sections },
    { path: "/course/:course", name: "TAs", component: TAs },
    { path: "/", component: courses },
    { path: "/*", component: courses }
];
const router = new VueRouter({
    routes: $route
});

describe('component-tas.vue', function () {
    it('lifecycle-testing', function () {
        // Create a new sections instance
        const wrapper = shallow(TAs, {
            localVue,
            router
        });
        // Testing when the fetch is resolved
        wrapper.vm.$nextTick(() => {
            expect(wrapper.vm.loading).to.be.equal(false);
            done();
        });
    });
});
