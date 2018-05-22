import { shallowMount } from '@vue/test-utils';
import { createLocalVue } from '@vue/test-utils';
import VueRouter from "vue-router";
import sections from '../src/components/component-sections.vue';
import courses from "../src/components/component-courses.vue";
import TAs from "../src/components/component-tas.vue";
import "isomorphic-fetch";
import chai from 'chai';

// Create a local vue-router instance in order to use route var
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
let expect = chai.expect;

describe('component-tas.vue', function () {
    it('lifecycle-testing', function () {
        // Create a new sections instance
        const wrapper = shallowMount(TAs, {
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
