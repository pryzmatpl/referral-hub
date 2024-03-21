<template lang="pug">
  div
    div
      .form-group
        input.form-control(
          v-model='newKeyword', type='text', @keyup.enter='addKeyword', :placeholder='placeholder'
          style="width: 100%; max-width:200px; display: inline"
        )
        button.btn.btn-info.ml-2.mr-3.form-control(@click='addKeyword', type='button' style="width: 150px; display: inline") 
         | Add
    table
      tr(v-for='keyword in keywords', :key='keyword')
        td
          span.badge.badge-pill.badge-light
            label
              |       {{keyword}}
              span.float-right.pl-2(:data-keyword='keyword', aria-hidden='true', @click='dropKeyword')
                | ×
        td
          b-form-radio-group.col-4(v-model='skills[keywords.indexOf(keyword)].exp', :key="keyword" style="display: inline")
            b-form-radio(value='1' v-b-tooltip="'1-2 years'" style="color: red") BASIC
            b-form-radio(value='2' v-b-tooltip="'3-5 years'") ADVANCED
            b-form-radio(value='3' v-b-tooltip="'5+ years'") EXPERT
        td(v-if="isParentProfile")
          b-input-group(append='years') 
            //if 1 then 'years' else 'years'
            b-form-input.col-3(type='number' v-model="skills[keywords.indexOf(keyword)].years")
    //div(v-for='keyword in keywords', :key='keyword')
      span.badge.badge-pill.badge-light
        label
          |       {{keyword}}
          span.float-right.pl-2(:data-keyword='keyword', aria-hidden='true', @click='dropKeyword')
            | ×
      b-form-radio-group.col-4(v-model='skills[keywords.indexOf(keyword)].exp', :key="keyword" style="display: inline")
        //v-if="!isParentProfile",
        b-form-radio(value='1') BASIC
        b-form-radio(value='2') ADVANCED
        b-form-radio(value='3') EXPERT
      //

</template>
<script>
export default {
  props: {
    keywords: {
      type: Array,
      required: false
    },
    
    skills: {
      type: Array,
      required: false
    },

    placeholder: {
      type: String,
      required: false
    }
  },

  watch: {
    keywords: function (val, oldVal) {
      //console.log(oldVal, ' - ', val)
    }
  },

  data () {
    return {
      isParentProfile: this.$route.path == '/profile',
      newKeyword: ''
    }
  },

  methods: {
    addKeyword (obj) {
      obj.preventDefault()
      if(this.keywords != undefined){
        console.log(this.keywords)
        if(!this.keywords.includes(this.newKeyword)){
          const updatedArray = this.pushToArray(this.newKeyword)
          this.$emit('keywords',updatedArray)
  
          const skillsArray = this.skills
          skillsArray.push({
            name: this.newKeyword,
            exp: 1,
            years: 1
          })
          this.$emit('skills', skillsArray)
          
          this.newKeyword = ''
        }
      } else {
        const skillsArray = this.skills
          skillsArray.push({
            name: this.newKeyword,
            exp: 1,
            years: 1
          })
          this.$emit('skills', skillsArray)
          
          this.newKeyword = ''
      }
    },

    dropKeyword (obj) {
      let array = this.keywords;
      const keywordName = $(obj.target).data('keyword');
      const strId = this.keywords.indexOf(keywordName);
      array.splice(strId, 1);
      this.$emit('keywords', array);

      const skillsArray = this.skills
      skillsArray.splice(strId,1)
      this.$emit('skills', skillsArray);        
    },
    
    pushToArray (keyword) {
      let array = this.keywords
      array.push(keyword)
      return array;
    }
  }
}
</script>
<style lang="sass" scoped>
  @import '@/assets/settings.sass'

  .badge
    font-size: 1.1em
    cursor: pointer
    font-weight: normal
    color: white
    margin: 3px
    background-color: #424242
    border-radius: 3px

</style>
