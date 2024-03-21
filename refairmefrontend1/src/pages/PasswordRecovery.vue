<template lang="pug">
  .col-12.d-flex.justify-content-center
    .card.mt-2
      .card-body
        h1.text-left(style="font-size: 36px") Enter new password
        form(action="" v-on:submit.prevent="setNewPassword()" method="post")
          //small.help-block(style="color: red") {{error}}
          .form-group
            label(for="email") Email
            input.form-control(
              v-model="email"
              type="email" name="email" id="email"
              placeholder="E-mail"
            )
          .form-group
            label(for='password') Password
            .input-group
              input.form-control(:type="[!isVisible ? 'password' : 'text']", name='password', placeholder="Password", v-model='password')
              .input-group-append
                //button.btn.btn-outline-secondary(type="button" @click="isVisible = !isVisible")
                .input-group-text
                  font-awesome-icon(
                    :icon="isVisible ? eyeIcon : eyeSlashIcon" 
                    @click="isVisible = !isVisible"
                  )
          p(v-if="message != ''") {{message}}
            br
            | You will be redirected to log in screen now.
          button(v-if="message == ''").btn.btn-info.btn-lg Set new password
</template>
<script>
import FontAwesomeIcon from '@fortawesome/vue-fontawesome'
import {
  faEye,
  faEyeSlash
} from '@fortawesome/fontawesome-free-solid'

export default {
  components: {
    FontAwesomeIcon
  },

  computed: {
    eyeIcon: () => faEye,
    eyeSlashIcon: () => faEyeSlash
  },

  data () {
    return {
      email: '',
      password: '',
      isVisible: false,
      message: ''
    }
  },

  methods: {
    setNewPassword () {
      const url = `/api/auth/password/recover`

      this.$store.state.backend
        .post(url, {
          email: this.email,
          password_recovery_hash: this.$route.query.password_recovery_hash,
          new_password: this.password
        })
        .then(ret => {
          this.status = ret.data.status
          this.message = ret.data.message
          console.log(ret)
          setTimeout(() => {
            this.$router.push('/auth/signin')
          }, 5000)
        })
        .catch(error => console.log("Error (mounted):",error))
    }
  }
}
</script>

