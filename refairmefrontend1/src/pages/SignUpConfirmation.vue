<template lang="pug">
  .card.mt-3.text-center
    .card-body
      h1 {{status}}
      p  {{message}}
      router-link(to='/auth/signin' tag="button").btn.btn-info Click here to sign in!
</template>
<script>
export default {
  //logic if confirmation is success or failed
  mounted () {
    console.log(this.$route.query.code)
    const url = `/auth/confirm?code=${this.$route.query.code}`
    
    this.$store.state.backend
      .get(url)
      .then(ret => {
        this.status = ret.data.status
        this.message = ret.data.message
        console.log(ret)
      })
      .catch(error => console.log("Error (mounted):",error))
  },

  data () {
    return {
      status: '',
      message: ''
    }
  }
}
</script>
