<template>
  <div class="form-check col-12">
    <input
        class="form-check-input"
        type="checkbox"
        :id="slug"
        :name="slug"
        :value="val"
        v-model="checked"
    />
    <label class="form-check-label" :for="slug">{{ name }}</label>
    <small v-if="error">Error placeholder</small>
  </div>
</template>
<script>
export default {
  props: ['value','name','val','error'],

  computed: {
    slug: vm => vm.toCamelCase(vm.name),
    checked: {
      get () {
        return this.value
      },
      set (val) {
        this.$emit('input', val)
      }
    }
  },

  methods: {
    toCamelCase (str) {
      return str.split(' ').map((word,index) => {
        if(index == 0){
          return word.toLowerCase();
        }
        return word.charAt(0).toUpperCase() + word.slice(1).toLowerCase();
      }).join('');
    }
  }
}
</script>
