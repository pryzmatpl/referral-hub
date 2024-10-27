export default {
  validateField (element){
    this.removeError(element)
    //console.log(element)
    switch (element.prop('for')) {
      //sign up
      case 'password':
      case 'passwordRepeat':
        return this.isPasswordMatch(element)
      //company profile
      case 'customFile':
        return this.hasFile(element);
        break;
      case 'companyName':
        return this.hasValue(element)
        break;
      case 'companyUrl':
        return this.hasValue(element)
        break;
      case 'contactPerson':
        return this.hasValue(element)
        break;
      case 'contactEmail':
        return this.isEmailValid(element) && this.isEmailInCompanyDomain(element)
        break;
      case 'aboutCompany':
        return this.hasValue(element)
        break;
      //about project
      case 'projectTitle':
        return this.hasValue(element)
        break;
      case 'whyWorkOnProject':
        return this.hasValue(element)
        break;
      case 'teamSize':
        return this.hasValue(element)
        break;
      case 'projectStage':
        return this.isChecked(element)
        break;
      case 'projectStack':
        return this.hasValue(element)
        break;
      case 'projectMethodology': //checkbox
        return this.isChecked(element)
        break;
      //about job
      case 'jobTitle':
        return this.hasValue(element)
        break;
      case 'jobDescription':
        return this.hasValue(element)
        break;
      case 'jobLocation':
        return this.hasValue(element)
        break;
      case 'contractType':
        return this.hasValue(element)
        break;
    }
  },
  isPasswordMatch (element) {
    // TODO
  },
  validateKeywords (keywords, element) {
    return keywords.length > 0 ? true : this.showError(element, 'At least one keyword required')
  },
  hasFile (element){
    return element.prev().prop('files').length != 0 || this.showError(element, '')
  },
  hasValue (element){
    return element.next().val().length > 0 || this.showError(element, 'Field can\'t be empty');
  },
  isEmailValid (element){
    const regex = /^(([^<>()\[\]\.,;:\s@\"]+(\.[^<>()\[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;
    return regex.test(element.val()) || this.showError(element, 'Invalid email');
  },
  isEmailInCompanyDomain (element){
    const email = element.val();
    const emailDomain = email.slice(email.indexOf('@') + 1)
    const sanitizedUrl = this.companyUrl.replace(/^(https?:\/\/)?(www.)?/,'')
    return (emailDomain.trim() === sanitizedUrl.trim())
            || this.showError(element, 'Email must be in company domain')
  },
  isChecked (element){
    let isChecked = false;
    element.siblings().each(function(){
      if($(this).find('input').is(':checked') == true){
        isChecked = true
      }
    })
    return isChecked || this.showError(element, 'Must be checked')
  },
  showError (element, message){
    const label = element.prop('for');
    //console.log(label)
    if(label === 'projectStage'
      || label === 'projectMethodology'
      || label === 'customFile'){
      element.css('color', 'red')
    } else if(label === 'mustHave'){
      element.next().next().css('color','red')
      element.next().next().text(message)
      element.next().next().show()
    } else {
      element.next('input,textarea').addClass('is-invalid')
      element.next('input,textarea').next('small').text(message)
    }
    return false
  },
  removeError (element){
    element = $(element)
    const label = element.prop('for');
    element.next('input,textarea').removeClass('is-invalid');
    element.next('small').text('')
    if(label === 'mustHave'){
      element.next().next().hide()
    } else if (label === 'projectStage'
              || label === 'projectMethodology'){
      element.css('color','inherit')
    }
  }
}
