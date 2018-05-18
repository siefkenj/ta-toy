import { expect } from 'chai'
import { mount } from 'avoriaz'
import Course from '../src/components/component-courses.vue'

describe('component-courses.vue', () => {
  it('has a root element with class id app-course', () => {
    const wrapper = mount(Course)
    expect(wrapper.is('#app-course')).to.equal(true)
  })
})
