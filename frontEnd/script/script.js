
    //  Tab switcher
    //  function openTab(tabId)
    //   {
    //   let contents = document.querySelectorAll('.tab-content');
    //   let buttons = document.querySelectorAll('.tab-btn');

    //   contents.forEach(c => c.classList.remove('active'));
    //   buttons.forEach(b => b.classList.remove('active'));

    //   document.getElementById(tabId).classList.add('active');
    //   event.target.classList.add('active');
    // }
//     function openTab(tabId, event) {
//   let contents = document.querySelectorAll('.tab-content');
//   let buttons = document.querySelectorAll('.tab-btn');

//   contents.forEach(c => c.classList.remove('active'));
//   buttons.forEach(b => b.classList.remove('active'));

//   document.getElementById(tabId).classList.add('active');

//   if (event) {
//     event.target.classList.add('active');
//   }
// }

function openTab(tabId, event) {
  let contents = document.querySelectorAll('.tab-content');
  let buttons = document.querySelectorAll('.tab-btn');

  // Hide all content and remove active class from all buttons
  contents.forEach(c => c.classList.remove('active'));
  buttons.forEach(b => b.classList.remove('active'));

  // Show the selected tab
  document.getElementById(tabId).classList.add('active');

  // Highlight the clicked button
  if (event) {
    event.target.classList.add('active');
  }
}


 let modal = document.getElementById("customModal");
    let cartList = document.getElementById("cartItems");
    let selectedMeal = "";
    let basePrice = 0;

    function openCustomization(meal, price) {
      selectedMeal = meal;
      basePrice = price;
      document.getElementById("mealName").textContent = `Customize ${meal}`;
      
      // reset checkboxes each time
      let checkboxes = document.querySelectorAll(".modal-content input[type='checkbox']");
      checkboxes.forEach(cb => cb.checked = false);

      modal.style.display = "flex";
    }

    function closeCustomization() {
      modal.style.display = "none";
    }

    function addToCart() {
      let checkboxes = document.querySelectorAll(".modal-content input[type='checkbox']");
      let selected = [];
      let extraCost = 0;

      checkboxes.forEach(cb => {
        if (cb.checked) {
          selected.push(cb.value);
          extraCost += parseInt(cb.dataset.price);
        }
      });

      let finalPrice = basePrice + extraCost;
      let item = document.createElement("li");
      item.textContent = `${selectedMeal} (${selected.join(", ") || "No customization"}) - ₦${finalPrice}`;
      cartList.appendChild(item);

      closeCustomization();
    }
