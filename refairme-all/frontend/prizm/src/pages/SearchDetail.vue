<template lang="pug">
  form
    h2 Essentials
    .form-row
      .form-group.col-12.col-md-4
        label(for="technology") Technology
        input.form-control(name="technology" placeholder="Front-end, Back-end")
      .form-group.col-12.col-md-4
        label(for="language") Language
        input.form-control(name="language")
      .form-group.col-12.col-md-4
        label(for="location") Location
        input.form-control(name="location" placeholder="e.g Wroclaw")
    .form-row
      .form-group.col-12.col-md-4
        label(for="salary") Salary
        input.form-control(name="salary")
      .form-group.col-12.col-md-4
        label(for="employmentType") Employment Type
        input.form-control(name="employmentType")
      .form-group.col-12.col-md-4
        label(for="startDate") Start date
        input.form-control(name="startDate" placeholder="e.g ASAP, 3 months")
    .form-row
      .form-group.col-12.col-md-4
        label(for="industry") Industry
        input.form-control(name="industry")
      .form-group.col-12.col-md-4
        label(for="companySize") Company size
        input.form-control(name="companySize")
      .form-group.col-12.col-md-4
        label(for="projectTeamSize") Project team size
        input.form-control(name="projectTeamSize" placeholder="e.g may vary, 3 people, >10 people")
    h2 Specs
    .form-row
      .form-group.col-12.col-md-3
        label(for="workload") Workload
        input.form-control(name="workload" placeholder="0%")
      .form-group.col-12.col-md-3
        label(for="workweek") Workweek
        input.form-control(name="workweek")
      .form-group.col-12.col-md-3
        label(for="holiday") Holiday
        input.form-control(name="holiday" placeholder="20 / 26 days")
      .form-group.col-12.col-md-3
        label(for="officeHours") Office hours
        input.form-control(name="officeHours" placeholder="9 - 17")
    .form-row
      // testing base components
      BaseFormGroupInput.col-md-4(name="Travel involved" error=true)
      BaseFormGroupInput.col-md-4(name="Remote possible" v-model="remotePossible")
      BaseFormGroupInput.col-md-4(name="Relocation package")
    h2 Project
    template(v-for="(item,index) in formResources.methodologies")
      BaseFormCheckbox(:name="item" v-model="selectedMethodologies", :val="item")
    span {{selectedMethodologies}}
</template>
<script>
import BaseFormGroupInput from '@/components/BaseFormGroupInput'
import BaseFormCheckbox from '@/components/BaseFormCheckbox'

export default {
  components: {
    BaseFormGroupInput,
    BaseFormCheckbox
  },

  data () {
    return {
      remotePossible: '',
      formResources: {
        methodologies: [
          'Issue tracking tool',
          'Knowledge repository',
          'Agile management',
          'Code reviews',
          'Pair programming',
          'Unit tests',
          'Integration tests',
          'Build server',
          'Static code analysis',
          'Version control system',
          'Testers',
          'QA Manager',
          'Freedom to choose tools',
          'One command build possible?',
          'Up and running ithin 2h?',
          'Commit on the first day?'
        ]
      },
      selectedMethodologies: []
    }
  }

}
</script>
