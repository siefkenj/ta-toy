import { shallowMount } from "@vue/test-utils";
import courses from "../src/components/component-courses.vue";
import "isomorphic-fetch";
import chai from "chai";
let expect = chai.expect;

describe("component-courses.vue", function() {
    let wrapper = null;
    beforeEach(function() {
        // runs before each test in this block
        // Create a new 'courses' instance
        wrapper = shallowMount(courses);
    });

    afterEach(function() {
        wrapper.destroy();
    });
    it("test fetch courses", async function() {
        let fetched = await fetch("http://localhost:3000/get_info.php");
        let fetchedJSON = await fetched.json();
        expect(fetchedJSON.TYPE).to.be.equal("courses");
        expect(fetchedJSON.STATUS).to.be.equal("OK");
        expect(Array.isArray(fetchedJSON.DATA)).to.be.true;
    });
    it("Loaded status should be false given incorrect url", async function() {
        await wrapper.vm.init("http://localhost:3000/get_info");
        expect(wrapper.vm.loaded).to.be.equal(false);
    });
    it("Loaded status should be true give correct url", async function() {
        await wrapper.vm.init("http://localhost:3000/get_info.php");
        expect(wrapper.vm.loaded).to.be.equal(true);
    });
    it("Error Status should be false given correct url", async function() {
        await wrapper.vm.init("http://localhost:3000/get_info.php");
        expect(wrapper.vm.err).to.be.equal(false);
    });
    it("Error Status should be true given incorrect url", async function() {
        await wrapper.vm.init("http://localhost:3000/get_info.");
        expect(wrapper.vm.err).to.be.equal(true);
    });
});
