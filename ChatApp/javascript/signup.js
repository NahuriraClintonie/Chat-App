const form = document.querySelector(".signup form"),
continueBtn = form.querySelector(".button input"),
errorText = form.querySelector(".error-text");

form.onsubmit = (e)=>{
    e.preventDefault();
}

continueBtn.onclick = ()=>{
    console.log("Signup button clicked!");

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "php/signup.php", true);

    xhr.onload = ()=>{
      console.log("Request finished. Status:", xhr.status);

      if(xhr.readyState === XMLHttpRequest.DONE){
          if(xhr.status === 200){
              let data = xhr.response;
              console.log("Server response:", data);

              if(data.trim() === "success"){
                console.log("Redirecting to users.php...");
                location.href="users.php";
              }else{
                errorText.style.display = "block";
                errorText.textContent = data;
                console.log("Error displayed:", data);
              }
          } else {
            console.error("Server returned non-200 status", xhr.status);
          }
      }
    }

    let formData = new FormData(form);
    console.log("Sending FormData:", [...formData.entries()]);
    xhr.send(formData);
}
