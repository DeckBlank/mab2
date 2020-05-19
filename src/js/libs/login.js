function saveUserLoginSession(user){
  window.localStorage.removeItem('mab_loged_user')
  
  window.localStorage.setItem('mab_loged_user',JSON.stringify({
    user_auth: user.user_login,
    user_email: user.user_email,
    user_mobile: user.user_mobile,
    session_token: user.user_pass,
    user_sector: user.user_sector
  }))
}

export {saveUserLoginSession}
