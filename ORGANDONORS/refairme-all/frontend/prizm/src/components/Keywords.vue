<template lang="pug">
  div
    div
      .input-group
        input.bg-light(v-model='newKeyword', type='text', @keyup.enter='addKeyword', placeholder='name a skill')
        .input-group-append
          button.btn.btn-outline-secondary(@click='addKeyword', type='button') Add
      //small
        | hint: use commas to add multiple keywords in one go
        br
      //small
        a(href='#', title='GitHub')
          | or add GitHub profile
    span.badge.badge-pill.badge-light.text-muted(v-for='keyword in keywords', :key='keyword')
      label
        //input(type='checkbox', name='optinkeyword', :data-keyword='keyword', checked='')
        |       {{keyword}}
      span.float-right.pl-2(:data-keyword='keyword', aria-hidden='true', @click='dropKeyword')
        | ×
</template>
<script>
export default {
  props: {
    keywords: {
      type: Array,
      required: true
    }
  },

  data () {
    return {
      newKeyword: ''
    }
  },

  methods: {
    addKeyword (obj) {
      obj.preventDefault();
      const updatedArray = this.pushToArray(this.newKeyword)
      this.$emit('keywords',updatedArray)
      this.newKeyword = '';
    },

    dropKeyword(obj) {
      let array = this.keywords;
      const keywordName = $(obj.target).data('keyword');
      const strId = this.keywords.indexOf(keywordName);
      array.splice(strId, 1);
      this.$emit('keywords', array);
    },
    
    pushToArray (keyword) {
      let array = this.keywords
      array.unshift(keyword);
      return array;
    }
  }
}
</script>
<style lang="sass" scoped>
  .badge
    font-size: 1.1em
    cursor: pointer
    font-weight: normal
    margin: 3px
  input
    border: 1px solid rgba(0,0,0,0.2)
    border-radius: 3px 0 0 3px
    padding-left: 10px
  .input-group-append
    button
      border-color: rgba(0,0,0,0.2)
</style>
