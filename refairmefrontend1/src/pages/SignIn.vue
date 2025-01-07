<template>
  <div>
    <h2 class="mt-3 mb-4 text-center">Welcome back to Refair.me</h2>
    <div class="container" style="margin-top:20px;margin-bottom:20px;">
      <div class="shadow row">
        <div class="col-12 inner-container">
          <div class="panel panel-default">
            <div class="panel-body">
              <div class="row">
                <div class="col-6 d-none d-md-block">
                  <h1 class="text-left" style="font-size: 60px;">Recruitment, with you in a driver seat</h1>
                  <p style="font-size: 18px; font-weight: 600">Every matching job, fast tracked and feedback to match your skills to best roles</p>
                </div>
                <div class="col"></div>
                <div class="col-12 col-md-5">
                  <h1 class="text-left" style="font-size: 50px;">{{ recovery ? 'Recover password' : 'Sign In' }}</h1>
                  <form action="" @submit.prevent="recovery ? recoverPassword() : login()">
                    <small class="help-block" style="color: red">{{ error }}</small>
                    <input class="form-control" v-model="email" type="email" name="email" id="email" placeholder="E-mail">
                    <label for="password">Password</label>
                    <input class="form-control mt-2" v-show="!recovery" v-model="password" type="password" name="password" id="password" placeholder="Password">
                    <p>{{ recoveryResponse }}</p>
                    <button class="btn btn-info btn-lg">
                      <span v-if="recovery">Recover password</span>
                      <span v-else>Sign In</span>
                    </button>
                    <div class="mt-1 d-flex justify-content-between">
                      <small class="float-left"><a href="#" @click="recovery = !recovery">Forgot password?</a></small>
                      <small class="float-right"><router-link to="/auth/signup">Don't have an account?</router-link></small>
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

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useStore } from 'vuex'
import { useRouter, useRoute } from 'vue-router'

const store = useStore()
const router = useRouter()
const route = useRoute()

// Reactive state
const password = ref('')
const email = ref('')
const error = ref('')
const recovery = ref(false)
const recoveryResponse = ref('')

// Computed
const isAuthenticated = computed(() => store.getters.isAuthenticated)

// Lifecycle hooks
onMounted(() => {
  error.value = route.params.info
})

// Methods
const login = async () => {
  try {
    const ret = await store.dispatch('signin', {
      email: email.value,
      password: password.value
    })

    const data = ret.data
    if (data.state === 'error') {
      error.value = data.message
    } else {
      if (route.query.job) {
        router.push(`/job/${route.query.job}`)
      } else {
        router.push({
          name: 'Profile',
          params: { tab: route.params.tab }
        })
      }
    }
  } catch (err) {
    console.log(err)
  } finally {
    password.value = ''
  }
}

const recoverPassword = async () => {
  try {
    const ret = await store.dispatch('passwordRecovery', {
      email: email.value
    })
    recoveryResponse.value = ret.data.message
  } catch (err) {
    console.log(err)
  }
}
</script>

<style lang="scss" scoped>
.container {
  margin-top: 20px;
  margin-bottom: 20px;
}

.inner-container {
  background: white;
  padding-bottom: 20px;
  padding-top: 20px;
  border-radius: 5px;
}

h1 {
  margin-top: 40px;
  margin-bottom: 20px;
  text-align: center;
}

.help-block {
  color: red;
}

.text-muted {
  margin-bottom: 10px;
}

button {
  width: 100%;
}

.shadow {
  box-shadow: 0 4px 24px 0 rgba(37, 38, 94, 0.1);
  border: 0;
}
</style>