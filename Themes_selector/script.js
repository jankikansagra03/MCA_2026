function changeTheme(theme) {
  //   document.body.className = theme;
  alert("Theme changed to: " + theme);
  document.documentElement.setAttribute("data-theme", theme);
}
