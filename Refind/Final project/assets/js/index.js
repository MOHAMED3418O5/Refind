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
// browseitems
 const foundItems = [
            {id: 1, category: "Electronics", type: "Smartphone", color: "Black", location: "Downtown", date: "2026-09-08", description: "Found near the main square."},
            {id: 2, category: "Bags", type: "Backpack", color: "Blue", location: "University Campus", date: "2026-09-07", description: "Blue waterproof backpack."},
            {id: 3, category: "Keys", type: "Car Keys", color: "Silver", location: "City Park", date: "2026-09-06", description: "Toyota key with a red strap."},
            {id: 4, category: "Accessories", type: "Wallet", color: "Brown", location: "Central Library", date: "2026-09-05", description: "Leather wallet, empty."}
        ];

        function displayItems(items) {
            const container = document.getElementById("itemsContainer");
            const noItemsMsg = document.getElementById("noItems");

            container.innerHTML = "";

            if (items.length === 0) {
                noItemsMsg.style.display = "block";
                return;
            }

            noItemsMsg.style.display = "none";

            items.forEach(item => {
                const card = document.createElement("div");
                card.className = "item-card";
                card.innerHTML = `
                    <div class="item-card-body">
                        <span class="item-tag">${item.category}</span>
                        <h3 class="item-title">${item.type} (${item.color})</h3>
                        <div class="item-details">
                            <span><i class="fa-solid fa-location-dot"></i> ${item.location}</span>
                            <span><i class="fa-regular fa-calendar"></i> ${item.date}</span>
                            <p>${item.description}</p>
                        </div>
                        <button class="match-btn" onclick="claimItem(${item.id})">I Think It's Mine</button>
                    </div>
                `;
                container.appendChild(card);
            });
        }

        function searchItems() {
            const category = document.getElementById("categoryFilter").value;
            const type = document.getElementById("typeFilter").value;
            const color = document.getElementById("colorFilter").value;
            const location = document.getElementById("locationFilter").value;
            const date = document.getElementById("dateFilter").value;

            const filtered = foundItems.filter(item => {
                const matchCategory = (category === "all" || item.category === category);
                const matchType = (type === "all" || item.type === type);
                const matchColor = (color === "all" || item.color === color);
                const matchLocation = (location === "all" || item.location === location);
                const matchDate = (!date || item.date === date);

                return matchCategory && matchType && matchColor && matchLocation && matchDate;
            });

            displayItems(filtered);
        }

        function resetFilters() {
            document.getElementById("categoryFilter").value = "all";
            document.getElementById("typeFilter").value = "all";
            document.getElementById("colorFilter").value = "all";
            document.getElementById("locationFilter").value = "all";
            document.getElementById("dateFilter").value = "";
            displayItems(foundItems);
        }

        function claimItem(id) {
            alert(`You selected item ID: ${id}. Proceeding to verification & messaging stage.`);
        }

        window.onload = () => {
            displayItems(foundItems);
        };