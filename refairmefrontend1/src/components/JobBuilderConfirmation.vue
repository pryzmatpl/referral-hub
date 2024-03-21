<template lang="pug">
  div(style="text-align: center")
    h1 Confirm adding job
    p Looks like all is good and you are ready to submit your job proposal. Click button below to submit!
    button.btn.btn-lg.btn-success(
      type='button'
      @click='submit'
      :disabled='!isAuthenticated'
    ) Add new job
</template>
<script>
export default{
  computed: {
    isAuthenticated: vm => vm.$store.getters.isAuthenticated
  },

  methods: {
    submit () {
      this.$emit('add-job')
    }
  }
}
</script>
