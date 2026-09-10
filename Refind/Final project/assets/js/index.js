/* ============================================================
   Refind — المنطق الرئيسي لتصفح العناصر
   البحث يتم بخوارزمية Linear Search (O(n)) مع عدّاد مقارنات
   ============================================================ */

const foundItems = [
  { id: 1, category: "إلكترونيات", type: "هاتف ذكي", color: "أسود", location: "وسط البلد", date: "2026-09-08", icon: "📱", description: "هاتف ذكي أسود اللون، يوجد غطاء خلفي أسود. تم العثور عليه بالقرب من الميدان الرئيسي." },
  { id: 2, category: "حقائب", type: "حقيبة ظهر", color: "أزرق", location: "الحرم الجامعي", date: "2026-09-07", icon: "🎒", description: "حقيبة ظهر زرقاء مقاومة للماء، تحتوي على كتب ودفاتر." },
  { id: 3, category: "مفاتيح", type: "مفاتيح سيارة", color: "فضي", location: "الحديقة العامة", date: "2026-09-06", icon: "🔑", description: "مفتاح سيارة تايوتا مع حزام أحمر صغير." },
  { id: 4, category: "إكسسوارات", type: "محفظة", color: "بني", location: "المكتبة المركزية", date: "2026-09-05", icon: "👛", description: "محفظة جلدية بنية اللون فارغة من الداخل." },
  { id: 5, category: "إكسسوارات", type: "ساعة يد", color: "فضي", location: "بوابة الجامعة", date: "2026-09-04", icon: "⌚", description: "ساعة يد فضية اللون بوجه أسود دائري." },
  { id: 6, category: "إلكترونيات", type: "سماعات أذن", color: "أبيض", location: "المكتبة", date: "2026-09-03", icon: "🎧", description: "سماعات أذن لاسلكية بيضاء داخل علبة شحن." },
  { id: 7, category: "ملابس", type: "سترة", color: "أحمر", location: "الكافيتيريا", date: "2026-09-02", icon: "🧥", description: "سترة رياضية حمراء مقاس M." },
  { id: 8, category: "إكسسوارات", type: "نظارة شمسية", color: "أسود", location: "الشارع الرئيسي", date: "2026-09-01", icon: "🕶️", description: "نظارة شمسية سوداء بإطار متوسط الحجم." }
];

/* ============================================================
   Linear Search — O(n)
   يمر على كل عنصر ويتحقق من مطابقة كل معايير الفلتر
   ============================================================ */
function linearSearchMessages(items, criteria) {
  const visited = [];
  const matches = [];

  for (let i = 0; i < items.length; i++) {
    const item = items[i];
    visited.push(i);

    if (
      (criteria.category === "all" || item.category === criteria.category) &&
      (criteria.type === "all" || item.type === criteria.type) &&
      (criteria.color === "all" || item.color === criteria.color) &&
      (criteria.location === "all" || item.location === criteria.location) &&
      (!criteria.date || item.date === criteria.date)
    ) {
      matches.push(item);
    }
  }
  return { matches, visited };
}

function displayItems(list) {
  const container = document.getElementById("itemsContainer");
  const noItemsMsg = document.getElementById("noItems");

  if (!container) return;
  container.innerHTML = "";

  if (list.length === 0) {
    noItemsMsg.style.display = "block";
    return;
  }

  noItemsMsg.style.display = "none";

  list.forEach(item => {
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
        <button class="match-btn" onclick="claimItem(${item.id})">أعتقد أنه لي</button>
      </div>
    `;
    container.appendChild(card);
  });
}

function searchItems() {
  const criteria = {
    category: document.getElementById("categoryFilter").value,
    type: document.getElementById("typeFilter").value,
    color: document.getElementById("colorFilter").value,
    location: document.getElementById("locationFilter").value,
    date: document.getElementById("dateFilter").value
  };

  document.getElementById("searchStats").style.display = "block";

  const result = linearSearchMessages(foundItems, criteria);
  displayItems(result.matches);

  document.getElementById("searchCount").textContent =
    `تم تنفيذ ${result.visited.length} مقارنة بخوارزمية البحث الخطي Linear Search (O(n)) وتم العثور على ${result.matches.length} نتائج.`;
}

function resetFilters() {
  document.getElementById("categoryFilter").value = "all";
  document.getElementById("typeFilter").value = "all";
  document.getElementById("colorFilter").value = "all";
  document.getElementById("locationFilter").value = "all";
  document.getElementById("dateFilter").value = "";
  document.getElementById("searchStats").style.display = "none";
  displayItems(foundItems);
}

function claimItem(id) {
  alert(`اخترت العنصر رقم ${id}. سيتم الانتقال إلى التحقق من الملكية والمحادثة.`);
}

document.addEventListener("DOMContentLoaded", () => {
  displayItems(foundItems);
});