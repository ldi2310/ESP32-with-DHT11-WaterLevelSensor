document.addEventListener("DOMContentLoaded", function () {
  const menuItem = document.querySelector(".menu-item");
  const submenu = menuItem.querySelector(".submenu");

  menuItem.addEventListener("mouseenter", function () {
    submenu.style.display = "block";
  });

  menuItem.addEventListener("mouseleave", function () {
    submenu.style.display = "none";
  });

  // Preventing default on 'Who We Serve' click
  const whoWeServe = document.getElementById("whoWeServe");
  whoWeServe.addEventListener("click", function (event) {
    event.preventDefault();
  });
});

  // Code khác cho các phần tử trang
  // Ví dụ: Xử lý form, hiệu ứng logo, và lời gọi API
  const signUpForm = document.querySelector(".footer-form");
  signUpForm.addEventListener("submit", function (event) {
    event.preventDefault();
    const email = document.querySelector('input[type="email"]').value;
    const firstName = document.querySelector('input[type="text"]').value;

    if (!email.includes("@")) {
      alert("Vui lòng nhập địa chỉ email hợp lệ.");
      return;
    }

    if (firstName.length < 1) {
      alert("Vui lòng nhập tên của bạn.");
      return;
    }

    console.log("Form data is valid: ", { email, firstName });
    alert("Cảm ơn bạn đã đăng ký!");
  });

  const logo = document.querySelector(".logo");
  logo.style.transition = "transform 0.5s ease";
  logo.addEventListener("mouseover", () => {
    logo.style.transform = "scale(1.1)";
  });
  logo.addEventListener("mouseout", () => {
    logo.style.transform = "scale(1)";
  });

  function updateContent() {
    fetch("https://api.example.com/data")
      .then((response) => response.json())
      .then((data) => {
        console.log("Data received from API: ", data);
        document.querySelector(".main-content").innerHTML =
          "<p>New content loaded!</p>";
      })
      .catch((error) => console.error("Error fetching data: ", error));
  }

  document
    .querySelector(".treeplotter-button")
    .addEventListener("click", updateContent);
});
