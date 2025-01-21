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
                <!-- Login buttons -->
                <div class="flex flex-col">
                  <GoogleLogin
                      :callback="handleGoogleLogin"
                  />
                  <button
                      @click="handleLinkedInLogin"
                  >
                    <img
                        src="../assets/Sign-In-Small---Default.png"
                        alt="Sign in with LinkedIn"
                    />
                  </button>
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
import { getCode, getUserInfo } from '../utils/signWithLinkedIn'

const router = useRouter()
const route = useRoute()
const store = useStore()

onMounted(() => {
  // Handle LinkedIn OAuth redirect
  const code = route.query.code
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
    const userData = await getUserInfo(code)
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

    if (signInResponse?.error === USER_DOES_NOT_EXIST) {
      // If user doesn't exist, show role selection modal and sign up
      const role = await showRoleSelectionModal()
      await store.dispatch('signup', {
        ...userData,
        role,
        password: '' // Empty password for social login
      })
    }

    router.push('/')
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