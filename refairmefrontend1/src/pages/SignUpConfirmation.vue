<template>
  <div class="card mt-3 text-center">
    <div class="card-body">
      <h1>{{status}}</h1>
      <p>{{message}}</p>
      <router-link to="/auth/signin" tag="button" class="btn btn-info">Click here to sign in!</router-link>
    </div>
  </div>
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
