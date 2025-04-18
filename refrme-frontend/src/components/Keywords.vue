<template>
  <div>
    <div>
      <div class="form-group">
        <input
            class="form-control"
            v-model="newKeyword"
            type="text"
            @keyup.enter="addKeyword"
            :placeholder="placeholder"
            style="width: 100%; max-width: 200px; display: inline"
        />
        <button
            class="btn btn-info ml-2 mr-3 form-control"
            @click="addKeyword"
            type="button"
            style="width: 150px; display: inline"
        >
          Add
        </button>
      </div>
    </div>
    <table>
      <tr v-for="keyword in keywords" :key="keyword">
        <td class="p-4">
          <span class="badge badge-pill badge-light">
            <label>{{ keyword }}</label>
            <span
                class="float-right pl-2"
                :data-keyword="keyword"
                aria-hidden="true"
                @click="dropKeyword"
            >
              ×
            </span>
          </span>
        </td>
        <td style="width: 10rem" class="p-1">
          <vue-slider
            v-model="skills[keywords.indexOf(keyword)].exp"
            :min="1"
            :max="3"
            :interval="1"
            :marks="marks"
            :tooltip="'focus'"
            :tooltip-formatter="tooltipFormatter"
          ></vue-slider>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/vue-slider-component@latest/theme/default.css">
        </td>
        <td v-if="isParentProfile" class="p-3">
          <b-input-group append="years">
            <b-form-input
                class="col-3"
                type="number"
                v-model="skills[keywords.indexOf(keyword)].years"
            />
          </b-input-group>
        </td>
      </tr>
    </table>
  </div>
</template>
<script>
import VueSlider from 'vue-slider-component'


export default {
  components: {
    VueSlider
  },
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
      marks: {
        1: 'BASIC',
        2: 'ADVANCED',
        3: 'EXPERT'
      },
      isParentProfile: this.$route.path == '/profile',
      newKeyword: '',
      skills: [],
      keywords: []
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
          this.$emit('keywords', [this.newKeyword])
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
    },

    tooltipFormatter(value) {
      const tooltips = {
        1: '1-2 years',
        2: '3-5 years',
        3: '5+ years'
      }
      return tooltips[value]
    }
  }
}
</script>
<style lang="scss" scoped>
  @use '@/assets/settings.scss' as settings;

.badge {
    font-size: 1.1em;
    cursor: pointer;
    font-weight: normal;
    color: white;
    margin: 3px;
    background-color: #424242;
    border-radius: 3px;
  }
</style>
<style lang="scss">
  .vue-slider-mark-label {
    font-size: 0.7em !important;
}
</style>
