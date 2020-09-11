function saveUserLoginSession(user){
  window.localStorage.removeItem('mab_loged_user')

  window.localStorage.setItem('mab_loged_user',JSON.stringify(user))
}

function updateUserLoginSession(field, value){
  let mab_loged_user = JSON.parse(window.localStorage.getItem('mab_loged_user'));
  
  if (field == 'user_metas.questionary') {
    window.localStorage.setItem('mab_loged_user',JSON.stringify({
      ...mab_loged_user,
      user_metas: {
        ...mab_loged_user.user_metas,
        questionary: value
      }
    }))
  }else if(field == 'user_metas.poll')   {
    window.localStorage.setItem('mab_loged_user',JSON.stringify({
      ...mab_loged_user,
      user_metas: {
        ...mab_loged_user.user_metas,
        poll: value
      }
    }))
  } else {
    window.localStorage.setItem('mab_loged_user',JSON.stringify({
      ...mab_loged_user,
      [field]: value
    }))
  }
}

function getUserLoged() {
  if (typeof mab !== 'undefined') {
    return mab;
  } else {
    return (window.localStorage.getItem('mab_loged_user')) ? window.localStorage.getItem('mab_loged_user') : false;
  }
}

export {saveUserLoginSession, updateUserLoginSession, getUserLoged}
