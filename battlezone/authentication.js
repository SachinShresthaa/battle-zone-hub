let password_holder = document.getElementById("password_holder");
let button = document.getElementById("button");
let message = document.getElementById("message");
let msg = document.getElementById("msg");
let email = document.getElementById("email");

msg.innerText = "";
message.innerText = "";
message.classList.remove("error_msg", "success_msg");

document.getElementById("password").addEventListener("input", validatePassword);
function validatePassword() {
   if (password.value.length < 8) {
      msg.innerText = "Password must be at least 8 characters long";
      msg.classList.add("error_msg"); 
      button.disabled = true;
      return false;
    }
    else {
      button.disabled = false;
      msg.innerText = "";
    }
 
   const strongPassword = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
  if (!strongPassword.test(password.value)) {
    msg.innerText =
      "Password must include uppercase, lowercase, number, and special character";
    msg.classList.add("error_msg");
    button.disabled = true;
    return false;
  }
}

document.getElementById("c_password").addEventListener("input", validatePasswords);

function validatePasswords() {
  const password = document.getElementById("password");
  const c_password = document.getElementById("c_password");

  message.innerText = "";
  message.classList.remove("error_msg", "success_msg");

  if (password.value !== c_password.value) {
    message.innerText = "Passwords do not match";
    message.classList.add("error_msg");
    return false;
  } else {
    message.innerText = "password matched successfully";
    message.classList.add("success_msg");
    button.disabled = false;
    return true;
  }
}

document.getElementById("email").addEventListener("input", validateEmail);
function validateEmail() {
   const emailMsg = document.getElementById("email_msg");
   const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

   if (!emailRegex.test(email.value)) {
     emailMsg.innerText = "Invalid email format";
     emailMsg.classList.add("error_msg");
     button.disabled = true;
     return false;
   }

   const commonDomains = ["gmail.com", "yahoo.com", "outlook.com"];
   const emailParts = email.value.split("@");
   if (emailParts.length > 1) {
     const domain = emailParts[1];
     if (!commonDomains.includes(domain)) {
       emailMsg.innerText = `Did you mean @${commonDomains[0]}?`;
       emailMsg.classList.add("error_msg");
     }
   } else {
     emailMsg.innerText = "";
   }
   $.ajax({
       url: 'authenticationLog.php',
       type: 'POST',
       data: { email: email.value },
       success: function(response) {
           const result = JSON.parse(response);
           if (result.exists) {
               emailMsg.innerText = "Email already exists!";
               emailMsg.classList.add("error_msg");
               button.disabled = true;
           } else {
               emailMsg.innerText = "";
               emailMsg.classList.remove("error_msg");
               button.disabled = false;
           }
       },
       error: function() {
           emailMsg.innerText = "Error checking email";
           emailMsg.classList.add("error_msg");
       }
   });

   emailMsg.classList.remove("error_msg");
   button.disabled = false;
   return true;
}
$(document).ready(function() {
  $('#fname').on('input', function() {
      let input = $(this);
      let fullName = input.val();
      
      // Function to capitalize first letter of each word
      function capitalizeWords(str) {
          // Split the string into words
          return str.replace(/\b\w+/g, function(word) {
              // Capitalize first letter, rest lowercase
              return word.charAt(0).toUpperCase() + word.slice(1).toLowerCase();
          });
      }

      // Get cursor position before changing value
      let start = this.selectionStart;
      let end = this.selectionEnd;

      // Apply capitalization
      let capitalizedName = capitalizeWords(fullName);
      
      // Only update if there's a change to avoid cursor jumping
      if (fullName !== capitalizedName) {
          input.val(capitalizedName);
          
          // Restore cursor position
          this.setSelectionRange(start, end);
      }

      // Validation
      let nameArray = capitalizedName.trim().split(/\s+/);
      let isValid = true;
      let errorMsg = '';

      if (capitalizedName.trim().length === 0) {
          isValid = false;
          errorMsg = 'Please enter your full name';
      }
      else if (nameArray.length < 2) {
          isValid = false;
          errorMsg = 'Please enter both your first name and surname';
      }
      else {
          for (let name of nameArray) {
              if (name.length === 0) continue;

              // Check if name contains only letters
              if (!/^[A-Za-z]+$/.test(name)) {
                  isValid = false;
                  errorMsg = 'Names must contain only letters';
                  break;
              }

              // Check minimum length for each name
              if (name.length < 2) {
                  isValid = false;
                  errorMsg = 'Each name must be at least 2 characters long';
                  break;
              }
          }
      }

      // Apply validation results
      if (!isValid) {
          $(this).addClass('error');
          $('#name_msg').text(errorMsg).addClass('error_msg');
          $('#button').prop('disabled', true);
      } else {
          $(this).removeClass('error');
          $('#name_msg').text('').removeClass('error_msg');
          $('#button').prop('disabled', false);
      }
  });
});
