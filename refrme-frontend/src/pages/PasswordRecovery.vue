<template>
  <div class="col-12 d-flex justify-content-center">
    <div class="card mt-2">
      <div class="card-body">
        <h1 class="text-left" style="font-size: 36px">Enter new password</h1>
        <form action="" @submit.prevent="setNewPassword()" method="post">
          <div class="form-group">
            <label for="email">Email</label>
            <input
              class="form-control"
              v-model="email"
              type="email"
              name="email"
              id="email"
              placeholder="E-mail"
            />
          </div>
          <div class="form-group">
            <label for="password">Password</label>
            <div class="input-group">
              <input
                class="form-control"
                :type="!isVisible ? 'password' : 'text'"
                name="password"
                placeholder="Password"
                v-model="password"
              />
              <div class="input-group-append">
                <div class="input-group-text">
                  <font-awesome-icon
                    :icon="isVisible ? eyeIcon : eyeSlashIcon"
                    @click="isVisible = !isVisible"
                  />
                </div>
              </div>
            </div>
          </div>
          <p v-if="message != ''">
            {{ message }}
            <br />
            You will be redirected to log in screen now.
          </p>
          <button v-if="message == ''" class="btn btn-info btn-lg">
            Set new password
          </button>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import {
  faEye,
  faEyeSlash
} from '@fortawesome/fontawesome-free-solid'

export default {
  components: {
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

