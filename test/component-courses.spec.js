import { shallow } from '@vue/test-utils';
import courses from '../src/components/component-courses.vue';
import "isomorphic-fetch";

describe('component-courses.vue', function () {
    it('lifecycle-testing', function () {
        // Create a new 'courses' instance
        const wrapper = shallow(courses);
        // Use nextTick to ensure that any promises are resolved before the
        // assertion is made
        wrapper.vm.$nextTick(() => {
            expect(wrapper.vm.loading).to.be.equal(false);
            done();
        });
    });
});
