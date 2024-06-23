<template>
  <div>
    <form class="mt-3" @submit.stop.prevent="addExperience">
      <h1>Most recent experience</h1>
      <div class="form-row">
        <div class="form-group col-4">
          <label>Company</label>
          <input
              class="form-control"
              v-model="newExperience.name"
              type="text"
              placeholder="Example: Evil Corp."
              required
          />
        </div>
        <div class="form-group col-4">
          <label>Position or role</label>
          <input
              class="form-control"
              v-model="newExperience.role"
              type="text"
              placeholder="Example: General Manager"
              required
          />
        </div>
        <div class="form-group col-4">
          <label>Salary</label>
          <input
              class="form-control"
              v-model="newExperience.salary"
              type="text"
              placeholder="fill in to compare to market"
          />
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-12">
          <label>What were your main responsibilites?</label>
          <textarea
              class="form-control"
              v-model="newExperience.responsibilities"
              rows="4"
              placeholder="Companies want to know what you learned and accomplished"
              required
          ></textarea>
          <div class="form-check">
            <input
                class="form-check-input"
                v-model="newExperience.currentJob"
                type="checkbox"
            />
            <label class="form-check-label">This is my current job</label>
          </div>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-4">
          <label>Start date</label>
          <input
              class="form-control"
              v-model="newExperience.start"
              type="date"
              required
          />
        </div>
        <div class="form-group col-4">
          <label>End date</label>
          <input
              class="form-control"
              v-model="newExperience.end"
              type="date"
              required
          />
        </div>
        <div class="form-group col-4">
          <label>Total years of relevant experience</label>
          <input
              class="form-control"
              v-model="newExperience.years"
              type="number"
              placeholder="eg. 4"
              required
          />
        </div>
      </div>
      <div class="form-row">
        <div class="col-12">
          <button class="btn btn-success float-right" type="submit">Add experience</button>
          <button class="btn btn-success float-right mr-2" @click="addAndCompareSalary" type="button">Add and compare salary</button>
        </div>
      </div>
    </form>
    <div
        class="mt-2"
        v-for="exp in experience"
        :key="exp.name"
        style="border: 1px solid black; border-radius: 3px; padding: 5px; background: #f4f6f9"
    >
      <div class="form-row">
        <div class="form-group col-6">
          <label>Company or project name</label>
          <p>{{ exp.name }}</p>
        </div>
        <div class="form-group col-6">
          <label>Position or role</label>
          <p>{{ exp.role }}</p>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-12">
          <label>What were your main responsibilites?</label>
          <p>{{ exp.responsibilities }}</p>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-6">
          <label>Start date</label>
          <p>{{ exp.start }}</p>
        </div>
        <div class="form-group col-6">
          <label>End date</label>
          <p>{{ exp.end }}</p>
        </div>
      </div>
    </div>
  </div>
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


