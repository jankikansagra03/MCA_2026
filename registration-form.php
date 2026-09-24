<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Registration Form - Validation Demo</title>
  <link rel="stylesheet" href="css/bootstrap.min.css" />
  <script src="js/bootstrap.bundle.min.js"></script>
  <script src="js/jquery.min.js"></script>
  <!-- <script src="js/jquery.validate.min.js"></script> -->
  <script src="js/validator.js"></script>


</head>

<body>
  <form id="registrationForm" novalidate>
    <h2>Create Your Account</h2>

    <!-- Text: required + alpha + min/max length -->
    <div class="field">
      <label for="fullName">Full Name</label>
      <input
        type="text"
        id="fullName"
        name="fullName"
        data-validation="required alpha min max"
        data-min="3"
        data-max="50" />
      <span class="error-message" id="fullNameError"></span>
    </div>

    <!-- Email -->
    <div class="field">
      <label for="email">Email Address</label>
      <input
        type="email"
        id="email"
        name="email"
        data-validation="required email" />
      <span class="error-message" id="emailError"></span>
    </div>

    <!-- Phone: required + numeric + min/max length -->
    <div class="field">
      <label for="phone">Phone Number</label>
      <input
        type="text"
        id="phone"
        name="phone"
        data-validation="required numeric min max"
        data-min="10"
        data-max="15" />
      <span class="error-message" id="phoneError"></span>
    </div>

    <!-- Password -->
    <div class="field">
      <label for="password">Password</label>
      <input
        type="password"
        id="password"
        name="password"
        data-validation="required strongPassword" />
      <div class="hint">
        8-25 chars, upper &amp; lower case, a number, and a special character.
      </div>
      <span class="error-message" id="passwordError"></span>
    </div>

    <!-- Confirm Password -->
    <div class="field">
      <label for="confirmPassword">Confirm Password</label>
      <input
        type="password"
        id="confirmPassword"
        name="confirmPassword"
        data-validation="required confirmPassword"
        data-password-id="password" />
      <span class="error-message" id="confirmPasswordError"></span>
    </div>

    <!-- Select dropdown -->
    <div class="field">
      <label for="country">Country</label>
      <select id="country" name="country" data-validation="required">
        <option value="">-- Select your country --</option>
        <option value="us">United States</option>
        <option value="in">India</option>
        <option value="uk">United Kingdom</option>
        <option value="ca">Canada</option>
      </select>
      <span class="error-message" id="countryError"></span>
    </div>

    <!-- Radio group -->
    <div class="field">
      <label>Gender</label>
      <div class="radio-group">
        <label><input
            type="radio"
            name="gender"
            value="male"
            data-validation="required" />
          Male</label>
        <label><input
            type="radio"
            name="gender"
            value="female"
            data-validation="required" />
          Female</label>
        <label><input
            type="radio"
            name="gender"
            value="other"
            data-validation="required" />
          Other</label>
      </div>
      <span class="error-message" id="genderError"></span>
    </div>

    <!-- Checkbox group with min/max items -->
    <div class="field">
      <label>Skills (choose 1 to 3)</label>
      <div class="checkbox-group">
        <label><input
            type="checkbox"
            name="skills"
            value="html"
            data-validation="required min-items max-items"
            data-min-items="1"
            data-max-items="3" />
          HTML</label>
        <label><input
            type="checkbox"
            name="skills"
            value="css"
            data-validation="required min-items max-items"
            data-min-items="1"
            data-max-items="3" />
          CSS</label>
        <label><input
            type="checkbox"
            name="skills"
            value="js"
            data-validation="required min-items max-items"
            data-min-items="1"
            data-max-items="3" />
          JavaScript</label>
        <label><input
            type="checkbox"
            name="skills"
            value="python"
            data-validation="required min-items max-items"
            data-min-items="1"
            data-max-items="3" />
          Python</label>
        <label><input
            type="checkbox"
            name="skills"
            value="php"
            data-validation="required min-items max-items"
            data-min-items="1"
            data-max-items="3" />
          PHP</label>
      </div>
      <span class="error-message" id="skillsError"></span>
    </div>

    <!-- File upload: required + filetype + filesize -->
    <div class="field">
      <label for="resume">Upload Resume (PDF or DOCX, max 500KB)</label>
      <input
        type="file"
        id="resume"
        name="resume"
        data-validation="required file filesize"
        data-filetype="pdf,docx"
        data-filesize="500"
        data-max-items="1" />
      <span class="error-message" id="resumeError"></span>
    </div>

    <!-- Single checkbox: terms -->
    <div class="field">
      <label>
        <input
          type="checkbox"
          id="terms"
          name="terms"
          data-validation="required" />
        I agree to the Terms &amp; Conditions
      </label>
      <span class="error-message" id="termsError"></span>
    </div>

    <button type="submit">Create Account</button>
  </form>

  <script>
    $(function() {
      $("form").on("submit", function(e) {
        if (window.validateForm && window.validateForm(this)) {
          alert("Form is valid! (Submission prevented in this demo.)");
          e.preventDefault();
        }
      });
    });
  </script>
</body>

</html>