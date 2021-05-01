<template>
  <div class="flex flex-col w-100 px-2 mx-auto items-center h-screen justify-center">
    <h5 class="text-center text-3xl font-bold mb-8 text-gray-700">Wisdom Friends Club of Nigeria</h5>

    <div class="w-full">
      <div class="" v-if="authenticationType === 'register'">
        <label for="firstName" class="form-label">First Name</label>
        <input
          id="firstName"
          name="first name"
          type="text"
          class="form-input focus:outline-none focus:bg-white"
          required
          v-model="input.first_name"
        />
      </div>
      <div class="" v-if="authenticationType === 'register'">
        <label for="lastName" class="form-label">Last Name</label>
        <input
          id="lastName"
          name="last name"
          type="text"
          class="form-input focus:outline-none focus:bg-white"
          required
          v-model="input.last_name"
        />
      </div>
      <div class="">
        <label for="email\" class="form-label">Email Address</label>
        <input
          id="email"
          name="email"
          type="email"
          class="form-input focus:outline-none focus:bg-white"
          required
          v-model="input.email"
        />
      </div>
      <div class="">
        <label for="password" class="form-label">Password</label>
        <input
          id="password"
          name="password"
          type="password"
          class="form-input focus:outline-none focus:bg-white"
          required
          v-model="input.password"
        />
      </div>
      <div class="" v-if="authenticationType === 'register'">
        <label for="passwordConfirmation" class="form-label">Password Confirmation</label>
        <input
          id="passwordConfirmation"
          name="password confirmation"
          type="password"
          class="form-input focus:outline-none focus:bg-white"
          required
          v-model="input.password_confirmation"
        />
      </div>

      <div class="mt-8 flex flex-col items-center">
        <button class="bg-purple-700 hover:bg-purple-900 w-full text-white rounded px-3 py-2">
          {{ authenticationType === 'register' ? 'Sign Up' : 'Sign In'}}
        </button>

        <router-link class="text-xs mt-8 text-blue-400" to="/forgot-password">Forgot password?</router-link>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "Authentication",
  props: {
    authenticationType: {
      type: String,
    },
  },
  data() {
    return {
      input: {
        first_name: '',
        last_name: '',
        email: '',
        password: '',
        password_confirmation: ''
      }
    };
  },
  methods: {
    performAuthentication() {

    },
  }
}
</script>

<style scoped>
.form-input {
  @apply appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-400 rounded py-3 px-4 mb-3 leading-tight;
}

.form-label {
  @apply uppercase block text-gray-700 text-sm font-bold mb-2
}
</style>
