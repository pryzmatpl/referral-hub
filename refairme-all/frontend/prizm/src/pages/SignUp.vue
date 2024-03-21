<template lang="pug">
.container
  h1 Join Refair.me
  .col-12.col-md-6.offset-md-3.inner-container
    .col-12
      .panel.panel-default
        .panel-heading

        .panel-body
          form(action='', v-on:submit.prevent='signUserUp()')
            small.help-block {{error}}
            .form-group
              label(for='firstname') First name
              input.form-control(type='text', name='firstname', placeholder='First name', v-model='firstname')
            .form-group
              label(for='lastname') Last name
              input.form-control(type='text', name='lastname', placeholder='Last name', v-model='lastname')
            .form-group
              label(for='email') Email
              input.form-control(type='email', name='email', placeholder='you@domain.com', v-model='email')
            .form-group
              label(for='password') Password
              input.form-control(type='password', name='password', placeholder="Password", v-model='password')
            .form-group
              label(for='passwordRepeat') Repeat password
              input.form-control(type='password', name='passwordRepeat', placeholder="Repeat password", v-model='passwordRepeat')
            .form-row
              .col
                // TODO ToS and Privacy Policy needed
                small.form-text.text-muted By signing up you agree to the Terms of Service and the Privacy Policy
            button.btn.btn-success.btn-lg(type='submit') Sign Up
</template>
<script>
import helpy from '../helpers.js'

export default {
  data () {
    return {
      firstname: '', // TODO shouldn't these be optional after all?
      lastname: '', // TODO shouldn't these be optional after all?
      email: '',
      password: '',
      passwordRepeat: '',
      error: '',
      group: ''
    }
  },

  methods: {
    signUserUp () {
      let headerRegister = {
        'Content-Type': 'multipart/form-data',
        'Authorization': 'Basic REGISTER',
        'Access-Control-Allow-Origin': '*',
      }

      this.$store.state.backend
        .post('/auth/signup', {
          email: this.email,
          password: this.password,
          chosengroup: this.group,
          headerRegister,
          dehashed: {
            SESSION_AUTH: false,
            SESSION_STATE: 2,
            SESSION_ID: '123ssdsdsd',
            ORIGIN: 'prizm',
            TIMESTAMP: '' //?
          }
        })
        .then(ret => {
          let data = ret.data;
          if(data.state === 'error'){
            this.error = data.message
          } else {
            this.$router.push({
              name: 'SignIn',
              params: {
                info: 'Please confirm your account in the email we sent you - you will be able to log in here after confirming your account'
              }
            })
          }
        })
        .catch(error => alert(error.message));
    },
    validateForm () {
      // TODO
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
</style>
