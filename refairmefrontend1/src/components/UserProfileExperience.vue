<template lang="pug">
  div
    form.mt-3(@submit.stop.prevent="addExperience")
      h1 Most recent experience
      .form-row
        .form-group.col-4
          label Company
          input.form-control(v-model="newExperience.name" type="text" placeholder="Example: Evil Corp." required)
        .form-group.col-4
          label Position or role
          input.form-control(v-model="newExperience.role" type="text" placeholder="Example: General Manager" required)
        .form-group.col-4
          label Salary
          input.form-control(v-model="newExperience.salary" type="text" placeholder="fill in to compare to market")
      .form-row
        .form-group.col-12
          label What were your main responsibilites?
          textarea.form-control(v-model="newExperience.responsibilites" rows=4 placeholder="Companies want to know what you learned and accomplished" required)
          .form-check
            input.form-check-input(v-model="newExperience.currentJob" type="checkbox")
            label.form-check-label This is my current job
      .form-row
        .form-group.col-4
          label Start date
          input.form-control(v-model="newExperience.start" type="date" required)
        .form-group.col-4
          label End date
          input.form-control(v-model="newExperience.end" type="date" required)
        .form-group.col-4
          label Total years of relevant experience
          input.form-control(v-model="newExperience.years" type="number" placeholder="eg. 4" required)
      .form-row
        .col-12
          button.btn.btn-success.float-right Add experience
          button.btn.btn-success.float-right.mr-2 Add and compare salary
    div.mt-2(v-for="exp in experience", :key="exp.name" style="border: 1px solid black;border-radius: 3px; padding: 5px; background: #f4f6f9")
      .form-row
        .form-group.col-6
          label Company or project name
          p {{exp.name}}
        .form-group.col-6
          label Position or role
          p {{exp.role}}
      .form-row
        .form-group.col-12
          label What were your main responsibilites?
          p {{exp.responsibilites}}
      .form-row
        .form-group.col-6
          label Start date
          p {{exp.start}}
        .form-group.col-6
          label End date
          p {{exp.end}}
</template>
<script>
export default {
  data () {
    return {
      newExperience: {
        name: '',
        role: '',
        responsibilites: '',
        currentJob: false,
        start: '',
        end: '',
        years: ''
      },
      experience: []
    }
  },

  methods: {
    addExperience () {
      this.experience.push({...this.newExperience})
      this.$emit('exp', this.experience)
    },

    deleteExperience () {
      // TODO
    }
  }
}
</script>
<style lang="sass" scoped>
  label
    font-weight: 700
</style>


