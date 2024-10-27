<template lang="pug">
.form-group.col-12
  label(:for="slug") {{name}}
  input.form-control(
    :name="slug"
    v-bind:value="value"
    v-on:input="$emit('input', $event.target.value)"
  )
  small(v-if="error") Error placeholder
</template>
<script>
export default {
  props: {
    name: {
      required: true,
      type: String
    },
    value: String,
    error: Boolean
  },

  computed: {
    slug: vm => vm.toCamelCase(vm.name)
  },
  
  methods: {
    toCamelCase (str) {
      return str.split(' ').map(function(word,index){
        if(index == 0){
          return word.toLowerCase();
        }
        return word.charAt(0).toUpperCase() + word.slice(1).toLowerCase();
      }).join('');
    }
  }
}
</script>
