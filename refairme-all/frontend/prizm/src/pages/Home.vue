<template lang="pug">
  div
    .search-panel
      .row
        .card.col-5
          .card-body
            h1 Find your dream job
            form
              .form-row.mb-2
                .col
                  //input.form-control(type="text" placeholder="Front-end")
                  select(style="width:100%")
                    option 1
                    option 2
                .col
                  input.form-control(type="text" placeholder="Technology")
              .form-row.mb-2
                .col
                  input.form-control(type="text" placeholder="Location")
                .col
                  input.form-control(type="text" placeholder="Salary")
              small.float-left
                router-link(to="/refinesearch") Refine search
              button.btn.btn-success.float-right 1234 results
    .row.mt-3(style="text-align: center")
      .col-4
        .card
          .card-body
            p Salary survey
      .col-4
        .card
          .card-body
            p Skill survey
      .col-4
        .card
          .card-body
            p Carrer comparison
</template>
<style lang="sass" scoped>
  .search-panel
    background-image: url("../assets/home_search.jpg")
    background-size: 100% 100%
    width: 100%
    height: 500px
    padding-top: 40px //because margin-top in .card is not working
    .card
      margin: 0 0 0 40px

  .col-4
    .card
      cursor: pointer
</style>
