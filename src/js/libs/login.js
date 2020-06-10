function saveUserLoginSession(user){
  window.localStorage.removeItem('mab_loged_user')
  
  window.localStorage.setItem('mab_loged_user',JSON.stringify({
    user_auth: user.user_login,
    user_email: user.user_email,
    user_mobile: user.user_mobile,
    session_token: user.user_pass,
    user_sector: user.user_sector,
    user_rol: user.user_rol,
    user_metas: user.user_metas
  }))
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

export {saveUserLoginSession, updateUserLoginSession}
