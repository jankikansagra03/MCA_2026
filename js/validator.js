/**
 * Form Validation Library
 * Supports both automatic event-delegated validation and manual invocation
 * via HTML inline attributes: oninput="validateField(this)" / onchange="validateField(this)"
 */

(function (window, $) {
  "use strict";

  // ============================================================
  // Helper: Find error span for a field reliably
  // ============================================================
  function getErrorSpan(field, name, id) {
    let identifier = name || id || "";

    // Safely look up by ID using native getElementById to avoid selector syntax errors with brackets like name="skills[]"
    let candidates = [
      identifier + "Error",
      identifier + "_err",
      identifier + "-error",
      (id || "") + "Error",
      (id || "") + "_err",
    ];

    for (let i = 0; i < candidates.length; i++) {
      if (candidates[i]) {
        let elem = document.getElementById(candidates[i]);
        if (elem) {
          return $(elem);
        }
      }
    }

    // Look for sibling or parent container error message elements
    let siblingErr = field.siblings(".error-message, .invalid-feedback");
    if (siblingErr.length > 0) {
      return siblingErr.first();
    }

    let parentErr = field
      .closest(".field, .form-group, .mb-3, div")
      .find(".error-message, .invalid-feedback");
    if (parentErr.length > 0) {
      return parentErr.first();
    }

    return $(); // empty jQuery collection if none found
  }

  // ============================================================
  // Individual Validation Rule Checkers
  // ============================================================
  const Validator = {
    isEmail: function (val) {
      return /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(val);
    },

    isAlpha: function (val) {
      return /^[A-Za-z\s]+$/.test(val);
    },

    isNumeric: function (val) {
      return /^[0-9]+$/.test(val);
    },

    isStrongPassword: function (val) {
      return /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,25}$/.test(
        val
      );
    },

    // ============================================================
    // validateField(input)
    // Checks ONE field against data-* attributes:
    // data-validation="required email min max"
    // Returns boolean: true if valid, false if invalid
    // ============================================================
    validateField: function (input) {
      if (!input) return true;
      let field = $(input);
      if (field.length === 0) return true;

      let type = field.attr("type");
      let name = field.attr("name") || "";
      let id = field.attr("id") || "";
      let rawVal = field.val();
      let value = rawVal ? String(rawVal).trim() : "";
      let errorSpan = getErrorSpan(field, name, id);

      let validationType = String(field.data("validation") || field.attr("data-validation") || "").trim();
      let minLength = parseInt(field.data("min") || field.attr("data-min"), 10) || 0;
      let maxLength = parseInt(field.data("max") || field.attr("data-max"), 10) || 9999;
      let fileSize = parseFloat(field.data("filesize") || field.attr("data-filesize")) || 0;
      let fileType = String(field.data("filetype") || field.attr("data-filetype") || "").toLowerCase();
      let maxItems = parseInt(field.data("max-items") || field.attr("data-max-items"), 10) || 1;
      let minItems = parseInt(field.data("min-items") || field.attr("data-min-items"), 10) || 0;

      let errorMessage = "";

      if (validationType !== "") {
        // Split by whitespace so "min-items" does not trigger "min", etc.
        let rules = validationType.split(/\s+/);

        // ---- required rule ----
        if (rules.includes("required")) {
          if (type === "checkbox") {
            let group = name ? $('input[name="' + name + '"][type="checkbox"]') : field;
            let checkedCount = group.filter(":checked").length;
            if (group.length > 1 ? checkedCount === 0 : !field.is(":checked")) {
              errorMessage =
                group.length > 1
                  ? "Please select at least one option."
                  : "You must agree to the Terms & Conditions.";
            }
          } else if (type === "radio") {
            let group = name ? $('input[name="' + name + '"]:checked') : (field.is(":checked") ? field : $());
            if (group.length === 0) {
              errorMessage = "Please select an option.";
            }
          } else if (type === "file") {
            let domInput = field[0];
            if (!domInput.files || domInput.files.length === 0) {
              errorMessage = "Please upload a file.";
            }
          } else if (field.is("select")) {
            if (value === "" || (field.find("option:selected").index() === 0 && value === "")) {
              errorMessage = "Please select an option.";
            }
          } else if (value === "") {
            errorMessage = "This field is required.";
          }
        }

        // ---- text-based rules (skip for checkbox/radio/file) ----
        if (
          !errorMessage &&
          type !== "checkbox" &&
          type !== "radio" &&
          type !== "file"
        ) {
          if (rules.includes("email") && value && !Validator.isEmail(value)) {
            errorMessage = "Please enter a valid email address.";
          }

          if (
            !errorMessage &&
            rules.includes("strongPassword") &&
            value &&
            !Validator.isStrongPassword(value)
          ) {
            errorMessage =
              "Password must be at least 8 characters, include uppercase, lowercase, number, and special character.";
          }

          if (!errorMessage && rules.includes("confirmPassword")) {
            let pwdId = field.data("password-id") || field.attr("data-password-id");
            let password = pwdId ? ($("#" + pwdId).val() || "").trim() : "";
            if (value !== password) {
              errorMessage = "Passwords do not match.";
            }
          }

          if (
            !errorMessage &&
            rules.includes("alpha") &&
            value &&
            !Validator.isAlpha(value)
          ) {
            errorMessage = "Only letters are allowed.";
          }

          if (
            !errorMessage &&
            rules.includes("numeric") &&
            value &&
            !Validator.isNumeric(value)
          ) {
            errorMessage = "Only numbers are allowed.";
          }

          if (
            !errorMessage &&
            rules.includes("min") &&
            value.length < minLength
          ) {
            errorMessage = `Must be at least ${minLength} characters.`;
          }

          if (
            !errorMessage &&
            rules.includes("max") &&
            value.length > maxLength
          ) {
            errorMessage = `Must be less than ${maxLength} characters.`;
          }
        }

        // ---- file-specific rules ----
        if (!errorMessage && type === "file") {
          let domInput = field[0];
          if (domInput.files && domInput.files.length > 0) {
            if (domInput.files.length > maxItems) {
              errorMessage = `You can only upload up to ${maxItems} file(s).`;
            } else {
              let file = domInput.files[0];
              let fileSizeKB = file.size / 1024;
              let fileExtension = file.name.split(".").pop().toLowerCase();

              if (
                rules.includes("file") &&
                fileType &&
                !fileType.split(",").map(s => s.trim()).includes(fileExtension)
              ) {
                errorMessage = `Only ${fileType} files are allowed.`;
              } else if (
                rules.includes("filesize") &&
                fileSize > 0 &&
                fileSizeKB > fileSize
              ) {
                errorMessage = `File size must be less than ${fileSize} KB.`;
              }
            }
          }
        }

        // ---- checkbox group min/max selected ----
        if (!errorMessage && type === "checkbox") {
          let selectedCount = (name ? $('input[name="' + name + '"][type="checkbox"]:checked') : field.filter(":checked")).length;
          if (rules.includes("max-items") && selectedCount > maxItems) {
            errorMessage = `You can only select up to ${maxItems} option(s).`;
          } else if (
            rules.includes("min-items") &&
            selectedCount < minItems
          ) {
            errorMessage = `You must select at least ${minItems} option(s).`;
          }
        }

        // ---- show/hide error on the page ----
        let group =
          (type === "checkbox" || type === "radio") && name
            ? $('input[name="' + name + '"]')
            : field;

        if (errorMessage) {
          if (errorSpan.length > 0) {
            errorSpan.text(errorMessage).show();
          }
          group.addClass("is-invalid").removeClass("is-valid");
        } else {
          if (errorSpan.length > 0) {
            errorSpan.text("").hide();
          }
          group.removeClass("is-invalid").addClass("is-valid");
        }

        // If this field is a main password, also trigger revalidation on any confirm-password field pointing to it
        if (id && $('[data-password-id="' + id + '"]').length > 0) {
          let confirmField = $('[data-password-id="' + id + '"]');
          if (confirmField.val()) {
            Validator.validateField(confirmField[0]);
          }
        }
      }

      return errorMessage === "";
    },

    // ============================================================
    // validateForm(form)
    // Validates every input in the form.
    // Returns boolean: true if valid, false if invalid
    // ============================================================
    validateForm: function (form) {
      let formElem = $(form);
      let isValid = true;
      let checkedGroups = {};

      formElem.find("input, textarea, select").each(function () {
        let field = $(this);
        let type = field.attr("type");
        let name = field.attr("name");

        if (type === "radio" || type === "checkbox") {
          if (name && checkedGroups[name]) {
            return;
          }
          if (name) checkedGroups[name] = true;
        }

        let fieldValid = Validator.validateField(this);
        if (!fieldValid) {
          isValid = false;
        }
      });

      return isValid;
    }
  };

  // ============================================================
  // Expose functions globally to window
  // Allows calling via oninput="validateField(this)" / onchange="validateField(this)"
  // ============================================================
  window.Validator = Validator;
  window.validateField = Validator.validateField;
  window.validateForm = Validator.validateForm;

  // ============================================================
  // Event Delegation for automatic live validation
  // Works with dynamic elements and standard form controls
  // ============================================================
  $(function () {
    $(document).on("input change blur", "input, textarea, select", function () {
      Validator.validateField(this);
    });

    $(document).on("submit", "form", function (e) {
      let isValid = Validator.validateForm(this);
      if (!isValid) {
        e.preventDefault();
      }
    });
  });

})(window, window.jQuery || window.$);
