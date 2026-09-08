const items = [
  {
    name: "Classic Silver Watch",
    category: "Accessories",
    icon: "⌚",
    description: "A silver watch with a black round face. The strap is metal and the watch looks simple and classic.",
    location: "University Gate",
    date: "Sep 07, 2026",
    characteristics: "Silver • Round • Metal"
  },
  {
    name: "Black Sunglasses",
    category: "Accessories",
    icon: "🕶️",
    description: "Black sunglasses with a simple frame. The lenses are dark and the frame is medium sized.",
    location: "Main Street",
    date: "Sep 06, 2026",
    characteristics: "Black • Dark lenses • Medium"
  },
  {
    name: "Wireless Earbuds",
    category: "Electronics",
    icon: "🎧",
    description: "Small white wireless earbuds inside a white charging case.",
    location: "Library",
    date: "Sep 05, 2026",
    characteristics: "White • Small • Wireless"
  },
  {
    name: "Brown Leather Wallet",
    category: "Accessories",
    icon: "👛",
    description: "A small brown leather wallet with several card slots and a simple design.",
    location: "Coffee Shop",
    date: "Sep 04, 2026",
    characteristics: "Brown • Leather • Small"
  }
];

function displayItems(list) {
  const container = document.getElementById("itemsContainer");
  const noItems = document.getElementById("noItems");

  if (!container) return;

  container.innerHTML = "";

  if (list.length === 0) {
    noItems.style.display = "block";
    return;
  }

  noItems.style.display = "none";

  list.forEach(item => {
    container.innerHTML += `
      <div class="item-card">
        <div class="item-top">
          <div class="item-icon">${item.icon}</div>
          <span class="tag">${item.category}</span>
        </div>
        <h3>${item.name}</h3>
        <p class="description">${item.description}</p>
        <div class="details">
          <span>📍 ${item.location}</span>
          <span>📅 ${item.date}</span>
        </div>
        <div class="details">
          <span>Characteristics: ${item.characteristics}</span>
        </div>
      </div>
    `;
  });
}

function searchItems() {
  const input = document.getElementById("searchInput").value.toLowerCase();
  const category = document.getElementById("categoryFilter").value;

  const filtered = items.filter(item => {
    const text = (item.name + " " + item.description + " " + item.location + " " + item.characteristics).toLowerCase();
    const matchesText = text.includes(input);
    const matchesCategory = category === "all" || item.category === category;
    return matchesText && matchesCategory;
  });

  displayItems(filtered);
}

function signupUser(event) {
  event.preventDefault();

  const name = document.getElementById("signupName").value;
  const email = document.getElementById("signupEmail").value;
  const password = document.getElementById("signupPassword").value;

  const user = { name: name, email: email, password: password };
  localStorage.setItem("finditUser", JSON.stringify(user));

  document.getElementById("signupMessage").textContent =
    "Account created successfully! You can now log in.";

  setTimeout(() => {
    window.location.href = "login.html";
  }, 1200);
}

function loginUser(event) {
  event.preventDefault();

  const email = document.getElementById("loginEmail").value;
  const password = document.getElementById("loginPassword").value;
  const savedUser = JSON.parse(localStorage.getItem("finditUser"));

  if (savedUser && email === savedUser.email && password === savedUser.password) {
    document.getElementById("loginMessage").textContent =
      "Login successful! Welcome " + savedUser.name + ".";
  } else {
    document.getElementById("loginMessage").textContent =
      "Email or password is incorrect.";
  }
}

displayItems(items);
