<template>
  <div style="text-align: center">
    <h1>Confirm adding job</h1>
    <p>Looks like all is good and you are ready to submit your job proposal. Click the button below to submit!</p>
    <button
        class="btn btn-lg btn-success"
        type="button"
        @click="submit"
        :disabled="!isAuthenticated"
    >
      Add new job
    </button>
  </div>
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
