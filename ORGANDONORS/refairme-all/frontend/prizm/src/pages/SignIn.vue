<template lang="pug">
.container(style="margin-top:20px;margin-bottom:20px;")
  .col-12.col-md-4.offset-md-4(style="background:rgba(173,216,230,0.25); padding-bottom:20px; padding-top:20px; border-radius: 5px;")
    .col-12
      .panel.panel-default
        .panel-heading(style="text-align: center") Sign In
        .panel-body
          form(action="" v-on:submit.prevent="login()" method="post")
            small.help-block(style="color: red") {{error}}
            .form-group
              label(for="email") Email
              input.form-control(type="email" name="email" id="email" placeholder="you@domain.com" v-model="email")
            .form-group
              label(for="password") Password
              input.form-control(type="password" name="password" id="password" v-model="password")
            button.btn.btn-outline-primary(style="width: 100%" type="submit") Sign In
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
      error: ''
    }
  },

  methods: {
    login () {
      this.$store.dispatch('signin', {
        locmail: this.$data.email,
        locpass: this.$data.password
      })
      .then(ret => {
        console.log(ret);
        const data = ret.data;
        if(data.state === 'error'){
          this.error = data.message
        } else {
          this.$router.push('/profilebuild')
        }
      })
      .catch(error => console.log(error))
      .finally(() => this.password = '');
    }
  }
}
</script>
