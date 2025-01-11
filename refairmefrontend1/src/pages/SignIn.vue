<template>
  <div>
    <h2 class="mt-3 mb-4 text-center">Welcome back to Refair.me</h2>
    <div class="container" style="margin-top:20px;margin-bottom:20px;">
      <div class="shadow row">
        <div class="col-12 inner-container">
          <div class="panel panel-default">
            <div class="panel-body">
              <div class="row d-flex justify-content-center">
                <div class="mb-5 col-6 d-none d-md-block">
                  <h1 class="text-start" style="font-size: 60px;">Recruitment, with you in a driver seat</h1>
                  <p style="font-size: 18px; font-weight: 600">Every matching job, fast tracked and feedback to match
                    your skills to best roles</p>
                </div>
                <div class="d-flex justify-content-center">
                  <GoogleLogin :callback="login" />
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
import { ref, computed, onMounted, defineAsyncComponent } from 'vue'
import { useStore } from 'vuex'
import { useRouter, useRoute } from 'vue-router'
import { decodeCredential } from 'vue3-google-login'
import { openModal } from '@kolirt/vue-modal'

import RoleModal from '@/components/RoleModal.vue'

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
const login = async (res) => {

  ////// TODO - LOGIC TO CHECK IF USER ALREADY EXISTS IN DATABASE OR SHOULD MODAL BE RENDERED ////
  const userData = decodeCredential(res.credential)
  const uniqueId = userData.sub
  const ret = await store.dispatch('signin', {
      uniqueId: userData.sub
    })

    const data = ret.data
    console.log(data)
    if (data.state === 'user not found') {
      runModal(userData)
      return
    }

  router.push('/')
  console.log(userData)
  return
  ////////////////////////////////////////////////////////////////////////////////////////////////




/*   try {
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
      router.push('/')
    }
  } catch (err) {
    console.log(err)
  } finally {
    password.value = ''
  } */
}

function runModal(userData) {
  openModal(RoleModal, {
    test: 'some props'
  })
    // runs when modal is closed via confirmModal
    .then((data) => {
      console.log('success', data.value)

      let headerRegister = {
        'Content-Type': 'multipart/form-data',
        'Authorization': 'Basic REGISTER',
      }

      store.state.backend
        .post('/auth/signup', {
          firstname: userData['given_name'],
          lastname: userData['family_name'],
          email: userData.email,
          uniqueId: userData.sub,
          password: 'bla',
          role: data.value,
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
          store.commit('SET_AUTH', true)
          router.push('/')
          if (data.state === 'error') {
            this.error = data.message
          } else {
          }
        })
        .catch(error => alert(error.message));

      /* router.push('/') */
    })
    // runs when modal is closed via closeModal or esc
    .catch(() => {
      console.log('catch')
    })
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