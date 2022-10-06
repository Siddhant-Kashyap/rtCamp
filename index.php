<!DOCTYPE html>
<html lang="en-US">

<head>
    <title>XKCD Challenge</title>
    <script> 


function email_validate(user_mail) {
    const validRegex = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    if (validRegex.test(user_mail)) {
        return true;
    }
    else {
        return false;
    }
}

function step_two(){
    document.getElementById("step-2").style.display = "block";
    document.getElementById("user_mail").disabled = true;
    document.getElementById("s-otp-button").value = "Submit";
    document.getElementById("s-otp-button").setAttribute("flag", "flag-2");
    document.getElementById("email-warn").innerHTML = "";
}

function final_step(){
    document.getElementById("form-style-div").style.display = "none";
    document.getElementById("tick-icon-div").style.display = "block";
}

function ajax_send_otp(user_mail) {
    console.log("ajax me aa gya");
    var email_warn = document.getElementById("email-warn");
    var s_otp_btn = document.getElementById("s-otp-button");
    s_otp_btn.value = "Sending...";
    email_warn.innerHTML = "";
    const xhttp = new XMLHttpRequest();
    xhttp.open("POST", "./users/php/send_otp.php", true);
    xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhttp.onload = function () {
        if (this.readyState == 4 && this.status == 200) {
            if(this.responseText.trim() == "OTP Sent Successfully"){
                console.log("step two");
                step_two();
            }
            else if(this.responseText.trim() == "Email is Already Verified"){
                s_otp_btn.value = "Send OTP";
                email_warn.innerHTML = "Email is Already Verified !";
                console.log("step two ke niche");
            }
            else if(this.responseText.trim() == "Invalid Email"){
                s_otp_btn.value = "Send OTP";
                email_warn.innerHTML = "Invalid Email !";
                console.log("step two ke niche k niche");

            }
            else{
                // s_otp_btn.value = "Send OTP";
                // email_warn = "Please try Again !";
                // console.log("step two ke niche shanu");
                step_two();
            }            
        }
    }
    xhttp.send("email="+user_mail);
}

function verify_otp(user_mail,otp){
    var otp_warn = document.getElementById("otp-warn");
    var s_otp_btn = document.getElementById("s-otp-button");
    s_otp_btn.value = "Verifying...";
    const xhttp = new XMLHttpRequest();
    xhttp.open("POST", "./users/php/verify_otp.php", true);
    xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhttp.onload = function () {
        if (this.readyState == 4 && this.status == 200) {
            if(this.responseText.trim() == "Email Verified"){
                final_step();
            }
            else if(this.responseText.trim() == "Invalid OTP"){
                s_otp_btn.value = "Submit";
                otp_warn.innerHTML = "Invalid OTP !";
            }
            else{
                s_otp_btn.value = "Submit";
                otp_warn.innerHTML = "Please try Again !";
            }           
        }
    }
    xhttp.send("email="+user_mail+"&otp="+otp);
}

function send_otp() {
    
    var email_warn = document.getElementById("email-warn");
    var otp_warn = document.getElementById("otp-warn");
    var user_mail = document.getElementById("user_mail").value;
    var otp = document.getElementById("otp").value;
    var s_otp_btn = document.getElementById("s-otp-button");
    if (s_otp_btn.getAttribute("flag") == "step-1") {
        if (user_mail != "" & email_validate(user_mail)) {
            ajax_send_otp(user_mail);
            console.log("clicked");
        }
        else {
            s_otp_btn.value = "Send OTP";
            email_warn.innerHTML = "Invalid Email !";
            console.log("clicked a");
        }
    }
    else {
        email_warn.innerHTML = "";
        if(otp != "" & otp.length==6){
            otp_warn.innerHTML = "";
            verify_otp(user_mail,otp);
            console.log("clicked a");
        }
        else {
            s_otp_btn.value = "Submit";
            otp_warn.innerHTML = "Invalid OTP !";
            console.log("clicked a");
        }        
    }
}








</script>
    <link rel="icon" href="https://avatars.githubusercontent.com/u/65281650?s=200&v=4" type="image/icon type">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./users/style/main.css">
</head>

<body>



    <div class="form-style">

        <div id="tick-icon-div">
            <img src="https://img.icons8.com/color/96/000000/approval--v3.gif" />
            <div>
                <span>Congratulations! your Email has been successfully verified.</span>
            </div>
            <a href="./users/php/unsubscribe.php">Click to Unsubscribe</a>
        </div>


        <div id="form-style-div">
            <h1>Sign Up Now!<span>Sign up to get random XKCD comics every five minutes!</span></h1>
            <form>

                <div id="step-1">
                    <div class="section"><span>1</span>Enter your Email Address</div>
                    <div class="inner-wrap">
                        <label>Email Address <input type="email" name="user_mail" id="user_mail" placeholder="Eg- abc@xkcd.com" /></label>
                        <label id="email-warn"></label>
                    </div>
                </div>

                <div id="step-2">
                    <div class="section"><span>2</span>Enter OTP</div>
                    <div class="inner-wrap">
                        <label>Enter OTP sent to your Email <input type="number" name="otp" id="otp" /></label>
                        <label id="otp-warn"></label>
                    </div>
                </div>

                <div class="button-section">
                    <input type="button" value="Send OTP" onclick="send_otp();" flag="step-1" id="s-otp-button" />
                </div>

            </form>
        </div>
    </div>

    <script src=""></script>

</body>

</html>