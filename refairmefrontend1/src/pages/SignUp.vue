<template>
  <div>
    <h2 class="mt-3 mb-4 text-center">Welcome to Refair.me</h2>
    <div class="container shadow">
      <div class="row">
        <div class="col-12 inner-container">
          <div class="panel panel-default">
            <div class="panel-body">
              <div class="row">
                <div class="col-6 d-none d-md-block">
                  <h1 class="text-left">Recruitment, with you in a driver seat</h1>
                  <p style="font-size: 18px; font-weight: 500">Every matching job, fast tracked and feedback to match your skills to best roles</p>
                </div>
                <div class="col"></div>
                <div class="col-12 col-md-5">
                  <h1 class="text-left" style="font-size: 50px">Sign Up for Free</h1>
                  <form action="" v-on:submit.prevent="signUserUp()">
                    <small class="help-block">{{error}}</small>
                    <div class="form-row">
                      <div class="col-6">
                        <input class="form-control" type="text" name="first_name" placeholder="First name" v-model="firstname">
                      </div>
                      <div class="col-6">
                        <input class="form-control" type="text" name="last_name" placeholder="Last name" v-model="lastname">
                      </div>
                    </div>
                    <div class="form-row mt-2">
                      <div class="col">
                        <input class="form-control" type="email" name="email" placeholder="E-mail" v-model="email">
                      </div>
                    </div>
                    <div class="form-row mt-2">
                      <div class="col">
                        <div class="input-group">
                          <input class="form-control" :type="[!isVisible ? 'password' : 'text']" name="password" placeholder="Password" v-model="password">
                          <div class="input-group-append">
                            <div class="input-group-text">
                              <font-awesome-icon :icon="isVisible ? eyeIcon : eyeSlashIcon" @click="isVisible = !isVisible"></font-awesome-icon>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <multiselect class="mt-2"
                                 v-model="role"
                                 :options="roleOptions"
                                 label="name"
                                 track-by="role"
                                 :searchable="false"
                                 :close-on-select="true"
                                 :show-labels="false"
                                 placeholder="Pick a role">
                    </multiselect>
                    <div class="form-row">
                      <div class="col">
                        <small class="form-text text-muted">
                          By signing up you agree to the <a href="/privacypolicy">Privacy Policy</a>
                        </small>
                      </div>
                    </div>
                    <p>{{responseFromServer}}</p>
                    <button class="btn btn-info btn-lg" :class="{disabled: responseFromServer != ''}" type="submit">Sign Up</button>
                    <div class="text-center">
                      <small>Already have an Refair.me account? <router-link to="/auth/signin">Log in</router-link></small>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</template>
<script>
import helpy from '../helpers.js'
import Multiselect from 'vue-multiselect'
import FontAwesomeIcon from '@fortawesome/vue-fontawesome'
import {
  faEye,
  faEyeSlash
} from '@fortawesome/fontawesome-free-solid'

export default {
  components: {
    FontAwesomeIcon,
    Multiselect
  },

  computed: {
    eyeIcon: () => faEye,
    eyeSlashIcon: () => faEyeSlash
  },

  data () {
    return {
      firstname: '',
      lastname: '',
      email: '',
      password: '',
      error: '',
      role: '',
      roleOptions: [
        { name: 'Developer', role: 'candidate'},
        { name: 'Recruiter', role: 'recruiter'}
      ],
      isVisible: false,
      responseFromServer: ''
    }
  },

  methods: {
    signUserUp () {
      if(this.validateForm()){
        let headerRegister = {
          'Content-Type': 'multipart/form-data',
          'Authorization': 'Basic REGISTER',
          'Access-Control-Allow-Origin': '*',
        }

        this.$store.state.backend
            .post('/auth/signup', {
              firstname: this.firstname,
              lastname: this.lastname,
              email: this.email,
              password: this.password,
              role: this.role.role,
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
                this.responseFromServer = 'Please confirm your account in the email we sent you.'
                /*
                this.$router.push({
                  name: 'SignIn',
                  params: {
                    info: 'Please confirm your account in the email we sent you - you will be able to log in here after confirming your account'
                  }
                })
                */
              }
            })
            .catch(error => alert(error.message));
      }
    },

    validateForm () {
      if(this.firstname == '' || this.lastname == '' || this.email == '' || this.password == '' || this.role == ''){
        this.error = 'Please fill all fields'
        return false
      } else {
        this.error = ''
        return true
      }
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
  font-size: 60px
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
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>