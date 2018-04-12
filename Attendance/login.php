
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Schedule</title>

<style type="text/css">
  div.btn.btn-default {
    border-width: 1px;
    color: #666666;
    background: transparent;
    border-color: #d6d6d6;
  }
  body{
    background-image: url("./assets/img/blur-bg.jpg");
    text-align: center;
    color: white;
    background-size: cover;
    will-change: transform;

  }
  .auth-main{
    margin-top: 150px;
    width: 100%;
  }
  .auth-block {
    width: 500px;
    margin: 0 auto;
    border-radius: 5px;
    background: rgba(0, 0, 0, 0.55);
    color: #fff;
    padding: 32px;
  }
  .form-control{
    background-color: rgba(255,255,255,0)!important;
    color: white !important;
    margin: 0 0 0 10;
  }
  .btn-auth {
    color: #ffffff !important;
  }
  .btn:hover {
    -webkit-transform: scale(1.2);
    transform: scale(1.2);
  }
  </style>
</head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<body>
<main class="auth-main">
  <div class="auth-block">
    <h1>Sign in to Schedule App.</h1>

    <form class="form-horizontal">
      <div class="form-group">
        <label for="inputEmail3" class="col-sm-2 control-label">Email</label>

        <div class="col-sm-10">
          <input type="text" class="form-control" id="inputEmail3" placeholder="Email">
        </div>
      </div>
      <div class="form-group">
        <label for="inputPassword3" class="col-sm-2 control-label">Password</label>

        <div class="col-sm-10">
          <input type="password" class="form-control" id="inputPassword3" placeholder="Password" onkeypress="onKeyPress(event)">
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-offset-1 col-sm-10">
          <div class="btn btn-default btn-auth" onclick="submit_click()">Sign in</div>

        </div>
      </div>
    </form>
  </div>
</main>
</body>
</html>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script type="text/javascript">
  function getCookie(cname) {
      var name = cname + "=";
      var decodedCookie = decodeURIComponent(document.cookie);
      var ca = decodedCookie.split(';');
      for(var i = 0; i < ca.length; i++) {
          var c = ca[i];
          while (c.charAt(0) == ' ') {
              c = c.substring(1);
          }
          if (c.indexOf(name) == 0) {
              return c.substring(name.length, c.length);
          }
      }
      return "";
  }
  function setCookie(cname,cvalue,exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires=" + d.toGMTString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
  }
  function sleep(milliseconds) {
    var start = new Date().getTime();
    for (var i = 0; i < 1e7; i++) {
      if ((new Date().getTime() - start) > milliseconds){
        break;
      }
    }
  }
  function submit_click(){
    var strEmail = document.getElementById("inputEmail3").value;
    var strPass = document.getElementById("inputPassword3").value;
    if( strEmail != ""){
      $.ajax({
        type: 'POST',
        url: './utils/dbAjax.php',
        data: {userMail: strEmail, userPass:strPass}
      }).done(function (d) {
        console.log(d);
        if( d == ""){
          document.getElementById("inputPassword3").value = "";
          setCookie("ScheduleUser", "", 1);
          setCookie("ScheduleUserRole", "", 1);
        } else{
          var arrBuf = [];
          arrBuf = d.split("??");
          setCookie("ScheduleUser", arrBuf[0], 1);
          setCookie("ScheduleUserRole", arrBuf[1], 1);
          if( arrBuf[1] != 'PowerUser'){
            setCookie("ScheduleUserId", arrBuf[2], 1);
            setCookie("ScheduleCompanyId", arrBuf[3], 1);
          }
          setTimeout(function(){
            if( getCookie("ScheduleUserRole") == "User"){
              window.location.href = "userInfo.php";
            } else{
              window.location.href = "index.php";
            }            
          }, 1000);
        }
      });
    }
  }
  function onKeyPress(event){
    if(event.keyCode == 13){
      submit_click();
    }
  }
  if( getCookie("ScheduleUser") != ""){
    window.location.href = "index.php";
  }
</script>