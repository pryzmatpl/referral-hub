<template lang="pug">
div
  h2.mt-3.mb-4.text-center Welcome back to Refair.me
  .container(style="margin-top:20px;margin-bottom:20px;").shadow
    .row
      .col-12.inner-container
        .panel.panel-default
          .panel-body
            .row
              .col-6.d-none.d-md-block
                  h1.text-left(style="font-size: 60px;") Recruitment, with you in a driver seat
                  p(style="font-size: 18px; font-weight: 600") Every matching job, fast tracked and feedback to match your skills to best roles
              .col
              .col-12.col-md-5
                h1.text-left(style="font-size: 50px") {{recovery ? 'Recover password' : 'Sign In'}}
                form(action="" v-on:submit.prevent="recovery ? recoverPassword() : login()" method="post")
                  small.help-block(style="color: red") {{error}}
                  //.form-group
                    //label(for="email") Email
                  input.form-control(
                    v-model="email"
                    type="email" name="email" id="email"
                    placeholder="E-mail"
                  )
                  //.form-group()
                    label(for="password") Password
                  input.form-control.mt-2(
                    v-show="!recovery"
                    v-model="password"
                    type="password" name="password" id="password" 
                    placeholder="Password"
                  )
                  p {{recoveryResponse}}
                  button.btn.btn-info.btn-lg 
                    span(v-if="recovery") Recover password
                    span(v-else) Sign In
                div.mt-1
                  small.float-left
                    a(href="#" @click="recovery = !recovery") Forgot password?
                  small.float-right
                    router-link(to="/auth/signup") Don't have an account?
</template>
<script>
import helpy from '../helpers.js'

export default {
  computed: {
    isAuthenticated: vm => vm.$store.getters.isAuthenticated
  },

  mounted(){
    this.error = this.$route.params.info
  },

  data () {
    return {
      password: '',
      email: '',
      error: '',
      recovery: false,
      recoveryResponse: ''
    }
  },

  methods: {
    login () {
      this.$store.dispatch('signin', {
        locmail: this.$data.email,
        locpass: this.$data.password
      })
      .then(ret => {
        const data = ret.data;
        if(data.state === 'error'){
          this.error = data.message
        } else {
          //if(this.$store.state.dehashedData.LAST_ROLE === 'candidate'){
            /*
            if(this.$route.query.job){
              this.$router.push(`/job/${this.$route.query.job}`)
            } else {
              this.$router.push('/jobs')
            }*/
          //} else {
            if(this.$route.query.job){
              this.$router.push(`/job/${this.$route.query.job}`)
            } else {
              this.$router.push({name: 'Profile', params: { tab: this.$route.params.tab}})
            }
          //}
        }
      })
      .catch(error => console.log(error))
      .finally(() => this.password = '');
    },
    
    recoverPassword () {
      this.$store.dispatch('passwordRecovery',{email: this.email})
        .then(ret => this.recoveryResponse = ret.data.message)
    }
  }
}
</script>
<style lang="sass" scoped>
  .container
    margin-top: 20px
    margin-bottom: 20px
  .inner-container
    background: white
    padding-bottom: 20px
    padding-top: 20px
    border-radius: 5px
  h1
    margin-top: 40px
    margin-bottom: 20px
    text-align: center
  .help-block
    color: red
  .text-muted
    margin-bottom: 10px
  button
    width: 100%
  .shadow
    box-shadow: 0 4px 24px 0 rgba(37, 38, 94, 0.1)
    border: 0
</style>
