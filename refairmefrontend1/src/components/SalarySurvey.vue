<template lang="pug">
  div
    .row
      .col-6
        .input-group
          input.form-control(v-model="salary" type="number" placeholder="enter you salary")
          .input-group-append
            button.btn.btn-outline-secondary(@click="updateChart") Update chart
    .row
      .col-6
        img(:src="'data:image/jpeg;base64,'+data")
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

