<template>
  <div class="form-group col-12">
    <label :for="slug">{{ name }}</label>
    <input
        class="form-control"
        :name="slug"
        :value="value"
        @input="$emit('input', $event.target.value)"
    />
    <small v-if="error">Error placeholder</small>
  </div>
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
