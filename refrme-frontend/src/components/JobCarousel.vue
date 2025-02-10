<template>
  <div>
    <!-- Slider main container -->
    <swiper-container class="mySwiper" effect="coverflow" grab-cursor="true" centered-slides="true" slides-per-view="2"
      coverflow-effect-rotate="0" coverflow-effect-stretch="100" coverflow-effect-depth="150"
      coverflow-effect-modifier="2.5" coverflow-effect-slide-shadows="true" loop="true" autoplay-delay="2000"
      loop-additional-slides="1">

      <swiper-slide class="bg-transparent p-2 border border-white rounded" v-for="job in jobOffers">
        <div class="glass-overlay"></div>
        <div class="job h-100 job-container rounded text-dark">
          <div class="rounded">
            <img :src="job.image">
            <div class=" position-relative px-3 h-100">
              <div class="company-name float-none w-auto mx-auto fs-6 text-light">{{ job.companyName }}</div>
              <h4 class="text-center fs-5">{{ job.jobTitle }}</h4>
              <div class="job-description">{{ job.description }}</div>
              <div class="d-flex justify-content-between">
                <div class="bg-white text-dark text-center rounded mt-2">{{ job.earnings }}</div>
                <div class="text-end my-2">{{ job.location }}</div>
              </div>
            </div>
          </div>
        </div>
      </swiper-slide>
    </swiper-container>
  </div>
</template>

<style lang="scss" scoped>
@import '@/assets/settings.scss';


swiper-container {
  width: 80%;
  padding-top: 50px;
  padding-bottom: 50px;
  position: fixed;
  bottom: -15%;
  left: 50%;
  transform: translate(-50%, 0%);
}

swiper-slide {
  background-position: center;
  background-size: cover;
  width: 40.5%;
  height: 350px;
  border-radius: 20px;
  position: relative;
}

.glass-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(255, 255, 255, 0.8);
  /* Semi-transparent */
  filter: blur(6px);
  backdrop-filter: blur(10px);
  /* Glass effect */
  -webkit-backdrop-filter: blur(10px);
  /* Safari support */
  border-radius: 10px;
  z-index: -1;
  /* Behind slide content, but inside the slide */
}

swiper-slide img {
  display: block;
  margin: auto;
  height: 8rem;
}

.company-name {
  display: inline-block;
  position: relative;
  top: -10px;
  left: 50%;
  transform: translateX(-50%);
  background-color: $primaryColor;
  padding: 0 10px;
  font-weight: bold;
}

.job-container {
  background-color: white;
}

.job-description {
  font-size: 0.8rem;
}

</style>

<script setup>
import { ref } from 'vue';
import logo1 from '../assets/logo1.png'
import logo2 from '../assets/logo2.png'
import logo3 from '../assets/logo3.png'
import logo4 from '../assets/logo4.png'

const items = ref([1, 2, 3, 4, 5, 6, 7, 8])

const jobOffers = [
  {
    companyName: "TechHub Innovations",
    jobTitle: "Front-End Developer",
    earnings: "$70,000 - $85,000 per year",
    location: "Austin, TX",
    description: "Develop responsive and dynamic user interfaces for web applications using modern frameworks like React and Angular.",
    image: logo1
  },
  {
    companyName: "GreenGrow Solutions",
    jobTitle: "Environmental Consultant",
    earnings: "$50,000 - $65,000 per year",
    location: "Portland, OR",
    image: logo2,
    description: "Advise companies on eco-friendly practices and help reduce their environmental impact through tailored solutions."
  },
  {
    companyName: "Skyline Ventures",
    jobTitle: "Data Analyst",
    earnings: "$60,000 - $75,000 per year",
    location: "Chicago, IL",
    image: logo3,
    description: "Analyze and interpret complex data sets to provide actionable insights and support strategic business decisions."
  },
  {
    companyName: "BrightFuture Education",
    jobTitle: "Online ESL Teacher",
    earnings: "$20 - $25 per hour",
    location: "Remote",
    image: logo4,

    description: "Teach English to non-native speakers in an engaging online environment and help improve their language proficiency."
  },
  {
    companyName: "Urban Architects",
    jobTitle: "Junior Architect",
    earnings: "$55,000 - $70,000 per year",
    location: "New York, NY",
    image: logo1,

    description: "Assist in designing innovative architectural projects, from residential buildings to commercial spaces."
  },
  {
    companyName: "Swift Logistics",
    jobTitle: "Supply Chain Manager",
    earnings: "$80,000 - $100,000 per year",
    location: "Atlanta, GA",
    image: logo2,

    description: "Oversee and optimize supply chain operations, ensuring efficiency and cost-effectiveness across the logistics network."
  },
  {
    companyName: "MindSpark Consulting",
    jobTitle: "Digital Marketing Specialist",
    earnings: "$55,000 - $68,000 per year",
    location: "Los Angeles, CA",
    image: logo3,

    description: "Create and manage digital marketing campaigns to increase brand visibility and drive customer engagement."
  },
  {
    companyName: "FusionTech AI",
    jobTitle: "Machine Learning Engineer",
    earnings: "$110,000 - $130,000 per year",
    location: "Seattle, WA",
    image: logo4,

    description: "Design and implement machine learning models and algorithms to solve complex real-world problems."
  },
  {
    companyName: "CarePath Health",
    jobTitle: "Registered Nurse",
    earnings: "$35 - $45 per hour",
    location: "Boston, MA",
    image: logo1,

    description: "Provide exceptional patient care in a fast-paced healthcare environment, focusing on recovery and wellness."
  },
  {
    companyName: "Peak Performance Gym",
    jobTitle: "Personal Trainer",
    earnings: "$25 - $35 per hour",
    location: "Denver, CO",
    image: logo2,

    description: "Design personalized fitness programs to help clients achieve their health and fitness goals effectively."
  },

];

</script>