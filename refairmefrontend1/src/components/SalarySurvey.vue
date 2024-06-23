<template>
  <div>
    <div class="row">
      <div class="col-6">
        <div class="input-group">
          <input
              class="form-control"
              v-model="salary"
              type="number"
              placeholder="enter your salary"
          />
          <div class="input-group-append">
            <button
                class="btn btn-outline-secondary"
                @click="updateChart"
            >
              Update chart
            </button>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-6">
        <img :src="'data:image/jpeg;base64,' + data" />
      </div>
    </div>
  </div>
</template>
<script>
export default {
  mounted () {
    this.$store.getters.backend
      .post('/comparesalary',{salary: 4000})
      .then(ret =>{
        console.log(ret)
        this.data = ret.data.plot_url
      })
  },

  data () {
    return {
      salary: '',
      data: ''
    }
  },

  methods: {
    updateChart () {
      this.$store.getters.backend
      .post('/comparesalary',{salary: this.salary})
      .then(ret =>{
        console.log(ret)
        this.data = ret.data.plot_url
      })
    }
  }
}
</script>

