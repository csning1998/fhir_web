function validateLogin() {
    var user_gmail = document.getElementById("user_gmail").value;
    var user_password = document.getElementById("user_password").value;

    if (user_gmail.trim() === '' || user_password.trim() === '') {
      alert("帳號和密碼不能為空");
      return false;
    }

    // 註解掉原本的登入成功與否的邏輯，因為現在交由後端 PHP 處理
    // var loginSuccessful = true;
    // if (loginSuccessful) {
    //   window.location.href = 'home.html';
    //   return false;
    // } else {
    //   alert("登入失敗  請重新登入");
    //   return false;
    // }
  }