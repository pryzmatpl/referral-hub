<template>
  <div>
    <h2 class="mt-3 mb-5 text-center fs-1">Welcome to Refair.me</h2>
    <div class="container" style="margin-top:20px;margin-bottom:20px;">
      <div class="row">
        <div class="inner-container">
          <div class="panel panel-default d-flex justify-content-center">
            <div class="panel-body w-75 position-relative p-2 d-flex justify-content-center">
              <div class="h-100 w-100 row d-flex justify-content-center rounded">
                
                <div class="mb-5 col-12 d-none d-md-block text-center">
                  <h1 style="font-size: 60px;">Recruitment, with you in a driver seat</h1>
                  <p style="font-size: 18px; font-weight: 600">Every matching job, fast tracked and feedback to match
                    your skills to best roles</p>
                </div>
                <!-- Login buttons -->
                <div class="d-flex flex-column align-items-center mb-5">
                  <GoogleLogin
                      :callback="handleGoogleLogin"
                      class="p-4"
                  />     
                  <div @click="handleLinkedInLogin" class="linkedin-button cursor-pointer rounded-pill overflow-hidden p-1 d-flex justify-content-center align-items-center">
                    <img
                        src="../assets/Sign-In-Small---Default.png"
                        alt="Sign in with LinkedIn"
                    />
                  </div>
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
import { onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { decodeCredential } from 'vue3-google-login'
import { useStore } from 'vuex'
import { openModal } from '@kolirt/vue-modal'
import RoleModal from '@/components/RoleModal.vue'
import { getCode, getUserInfo, getAccessToken } from '@/utils/signWithLinkedIn'

const router = useRouter()
const route = useRoute()
const store = useStore()

onMounted(() => {
  // Handle LinkedIn OAuth redirect
  const code = route.query.code
  console.log(code)
  if (code) {
    handleLinkedInCallback(code)
  }
})

const handleGoogleLogin = async (response) => {
  try {
    const userData = decodeCredential(response.credential)
    await authenticateUser({
      uniqueId: userData.sub,
      email: userData.email,
      firstName: userData.given_name,
      lastName: userData.family_name,
      provider: 'google'
    })
  } catch (error) {
    console.error('Google login error:', error)
  }
}

const handleLinkedInLogin = () => {
  getCode() // Redirect to LinkedIn auth
}

const handleLinkedInCallback = async (code) => {
  try {
    const accessToken = await getAccessToken(code)

    const userData = await getUserInfo(accessToken['access_token'])

    await authenticateUser({
      uniqueId: userData.sub,
      email: userData.email,
      firstName: userData.given_name,
      lastName: userData.family_name,
      provider: 'linkedin'
    })
  } catch (error) {
    console.error('LinkedIn login error:', error)
  }
}

const USER_DOES_NOT_EXIST = 'user does not exist';

const authenticateUser = async (userData) => {
  try {
    // Try to sign in first
    const signInResponse = await store.dispatch('signin', {
      uniqueId: userData.uniqueId
    })
    const errorMessage = signInResponse.response?.error;
    console.log(errorMessage)
    console.log(signInResponse)
    if ( errorMessage === USER_DOES_NOT_EXIST) {

      // If user doesn't exist, show role selection modal and sign up
      const role = await showRoleSelectionModal()
      await store.dispatch('signup', {
        ...userData,
        role,
        password: '' // Empty password for social login
      })
      await store.dispatch('signin', {
        uniqueId: userData.uniqueId
      })
    }

    await router.push('/')
  } catch (error) {
    console.error('Authentication error:', error)
  }
}

const showRoleSelectionModal = () => {
  return new Promise((resolve, reject) => {
    openModal(RoleModal)
        .then(response => resolve(response.value))
        .catch(reject)
  })
}
</script>

<style lang="scss" scoped>
.inner-container {
  background: transparent;
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

img {
  cursor: pointer;
  width: 90%;
}

.glass-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(255, 255, 255, 0.8);
  /* Semi-transparent */
  filter: blur(6px);
  backdrop-filter: blur(10px);
  /* Glass effect */
  -webkit-backdrop-filter: blur(10px);
  /* Safari support */
  border-radius: 10px;
  z-index: -1;
  /* Behind slide content, but inside the slide */
}

.linkedin-button {
  background-color: #0077b5;
  width: 22rem;
}


</style>