import Vue from 'vue'
import { shallowMount, createLocalVue } from '@vue/test-utils'
import Vuex from 'vuex'
import storeConf from '../../../prizm-vuex.js'
import { cloneDeep } from 'lodash'
import JobListing from '@/pages/JobListing'

describe('JobListing.vue', () => {

  //const Constructor = Vue.extend(JobListing)
  const cmp = shallowMount(JobListing)
  //const vm = new Constructor().$mount()

  test('should render correct contents', () => {
    expect(cmp.vm.$el.querySelector('th').textContent)
      .toEqual('Job Title')
  })

  test('vuex store', () => {
    const localVue = createLocalVue()
    localVue.use(Vuex)
    const store = new Vuex.Store(cloneDeep(storeConf))
    expect(store.state.isAuthenticated).toBe(false)
    //store.commit('increment')
    //expect(store.state.count).toBe(1)
  })

  /*
  it('has the expected html structure', () => {
    expect(cmp.vm.element).toMatchSnapshot()
  })*/
})
